import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch all collection
 *
 * @param params
 * @returns dispatch
 */
export function _fetchAllTemplates(params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('template', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_TEMPLATES, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                // exceptionHandler(error);
            });
    }
}

/**
 * save collection
 *
 * @param data
 * @returns dispatch
 */
export function _saveTemplate(data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ""})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('template', {template: data})
                .then(function (response) {

                    dispatch({type: ActionTypes.SAVE_TEMPLATE, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllTemplates());
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        }
        else {
            instance.put('template/' + data.id, {template: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_TEMPLATE, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllTemplates());
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
 * Remove collection
 *
 * @param data
 * @returns dispatch
 */
export function _deleteTemplate(data) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('template/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_TEMPLATE, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllTemplates());
            })
            .catch(function (error) {
                // exceptionHandler(error);
            });
    }
}