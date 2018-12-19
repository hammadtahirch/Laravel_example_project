import axios from 'axios';
import Config from "../config/app-constants";
import history from "../../History";

export function isLogin() {
    if (!getSession('login')) {
        return true;
    }
}

export function setSession(key, value) {
    window.sessionStorage.setItem(key, JSON.stringify(value))
}

export function getSession(key) {
    try {
        return JSON.parse(window.sessionStorage.getItem(key));
    } catch (e) {
        console.log();
    }

}

export function _headerContentType() {
    return 'application/json';
}

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

export function _setHeaders() {
    return axios.create({
        baseURL: Config.API_SERVER,
        timeout: 1000,
        headers: {'Content-Type': _headerContentType(), 'Authorization': _headerAuth()}
    });
}

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

export function exceptionHandler(error) {
    debugger;

    console.log(error);
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