import axios from 'axios';
import Config from "../config/app-constants";
import history from "../../History";

/**
 * check user session exist or not
 *
 * @returns {boolean}
 */
export function isLogin() {
    if (!getSession('login')) {
        return true;
    }
}

/**
 * set local storage session
 *
 * @param key
 * @param value
 */
export function setSession(key, value) {
    window.sessionStorage.setItem(key, JSON.stringify(value))
}

/**
 * get local storage session
 *
 * @param key
 * @returns {any}
 */
export function getSession(key) {
    try {
        return JSON.parse(window.sessionStorage.getItem(key));
    } catch (e) {
        console.log();
    }

}

/**
 * set content header
 *
 * @returns {string}
 * @private
 */
export function _headerContentType() {
    return 'application/json';
}

/**
 * set auth header
 *
 * @returns {*}
 * @private
 */
export function _headerAuth() {
    let login = getSession('login');
    try {
        login = login.data.success;
    } catch (e) {
        console.log(e);
    }

    if (login) {
        return 'Bearer ' + login.token;
    } else {
        return false;
    }
}

/**
 * set headers for axios
 *
 * @returns {AxiosInstance}
 * @private
 */
export function _setHeaders() {
    return axios.create({
        baseURL: Config.API_SERVER,
        timeout: 180000,
        headers: {'Content-Type': _headerContentType(), 'Authorization': _headerAuth(),"X-CSRF-TOKEN":CSRF_TOKEN}
    });
}

/**
 * base 64 file data
 *
 * @param file
 * @returns {Promise<any>}
 */
export function getBase64(file) {
    return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            resolve(reader.result);
        };
        reader.onerror = function (error) {
            reject(error);
            console.log('Error: ', error);
        };
    });
}

/**
 * error function to redirect pages
 *
 * @param error
 */
export function exceptionHandler(error) {
    let code = error.response;
    switch (code.status) {
        case 403:
            history.push('/admin/403');
            break;
        case 404:
            history.push('/admin/404');
            break;
        case 500:
            history.push('/admin/500');
            break;
        default:
            break;
    }

}