import history from '../../History';
import axios from 'axios';
import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import Config from "../config/app-constants";
import ActionTypes from "../constant/constant";

/**
 * fetch all user roles
 *
 * @returns {Function}
 */
export function _fetchAllRoles() {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('role', {params: {}})
            .then(function (response) {

                dispatch({type: ActionTypes.FETCH_ROLES, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                dispatch({type: ActionTypes.ERROR, payload: error.response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            });
    }
}

/**
 * fetch all users
 *
 * @param params
 * @returns dispatch
 */
export function _fetchAllUser(params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('user', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_USERS, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                dispatch({type: ActionTypes.ERROR, payload: error.response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            });
    }
}

/**
 * Remove users
 *
 * @param data
 * @returns dispatch
 */
export function _deleteUser(data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('user/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_USER, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllUser());
            })
            .catch(function (error) {
                dispatch({type: ActionTypes.ERROR, payload: error.response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            });
    }
}

/**
 * Save user
 *
 * @param data
 * @returns {Function}
 */
export function _saveUser(data) {
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ''})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('user', {user: data})
                .then(function (response) {

                    dispatch({type: ActionTypes.SAVE_USER, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllUser());
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});

                });
        } else {
            instance.put('user/' + data.id, {user: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_USER, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllUser());
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});

                });
        }
    }
}

/**
 * user sign function
 *
 * @param user
 * @returns dispatch
 * @private
 */
export function _signInAction(user) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ""})
        dispatch({type: ActionTypes.LOADING, payload: true});
        axios.post(Config.API_SERVER + 'login', user)
            .then(function (response) {
                setSession('login', response);
                dispatch({type: ActionTypes.LOADING, payload: false});
                history.push('/admin/dashboard');
            })
            .catch(function (error) {
                // exceptionHandler(error);
                dispatch({type: ActionTypes.ERROR, payload: error.response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            });
    }
}

/**
 * Remove user session from browser local storage
 *
 * @returns dispatch
 * @private
 */
export function _signOutAction() {
    return dispatch => {
        const instance = _setHeaders();
        instance.post(Config.API_SERVER + 'sign_out', '')
            .then(function (response) {
                window.sessionStorage.clear();
                history.push('/admin/login');
            })
            .catch(function (error) {
                // exceptionHandler(error);
            });
    }
}
