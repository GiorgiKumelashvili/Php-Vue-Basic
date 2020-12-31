import Vue from 'vue';
import axios from 'axios';
import Config from './Config';


// Base configs
const { texts, cookies } = Config;
const BASE_BACK_API_URL = `${process.env.VUE_APP_BACK_BASE_URL}api`;
const BASE_URL = process.env.VUE_APP_FRONT_BASE_URL.toString();

class Back {
    static async Service(methodNum, obj) {
        let data = await Back.Ser(methodNum, obj);

        if (data.message === texts.access_token_expiration_txt) {
            data = await Back.Ser(methodNum, obj);
        }

        return data;
    }

    static async Ser(methodNum, obj) {
        if (!methodNum || !Number.isInteger(methodNum)) {
            throw new Error("[GIO] Method Number is either not number or anything at all");
        }

        const data = {
            ...obj,
            'identifier': Vue.$cookies.get(cookies.identifier)
        }

        const headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${Vue.$cookies.get(cookies.access_token)}`
        };

        // Return new Promise
        return axios({
            method: 'post',
            url: `${BASE_BACK_API_URL}/${methodNum}`,
            headers,
            data: JSON.stringify(data)
        })
            .then(response => {
                let { statuscode, message } = response.data;

                // Access token expired
                if (statuscode === 0 && message === texts.access_token_expiration_txt) {
                    Back.generateAccessToken();
                    return { message };
                }

                return response.data;
            })
            .catch(err => console.log(err));
    }

    static async Auth(url, obj) {
        if (!url || typeof url !== 'string') {
            throw new Error("[GIO] Url is incorrect (not string) or doesnt exist")
        }

        // Return new Promise
        return axios({
            method: 'post',
            url: `${BASE_BACK_API_URL}/${url}`,
            data: JSON.stringify(obj)
        })
            .then(response => response.data);
    }

    static generateAccessToken() {
        const headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${Vue.$cookies.get(cookies.refresh_token)}`
        };

        const data = {
            data: "refreshToken"
        }

        axios({
            method: 'post',
            url: `${BASE_BACK_API_URL}/refreshtoken`,
            data: JSON.stringify(data),
            headers
        })
            .then(response => {
                let main = Object.assign({}, response.data);

                // Refresh token expired
                if (main.statuscode === 0 && main.message === texts.refresh_token_expiration_txt) {
                    // Show Message
                    alert("Sorry your account session has expired pleas login aagain");

                    // Remove cookies
                    Back.removeCookies();

                    // Redirect to login page
                    window.location.href = `${BASE_URL}/auth/login`;

                    return null;
                }

                // save new token
                Vue.$cookies.set(cookies.access_token, main.data.accessToken)
            })
            .catch(err => console.log(err));
    }

    static removeCookies() {
        Object.values(cookies).forEach(cookie => {
            Vue.$cookies.remove(cookie);
        });
    }
}

export default Back;
