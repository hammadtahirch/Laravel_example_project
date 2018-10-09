import {_setHeaders, exceptionHandler} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

export function _fetchAllPermission(params) {
    debugger;
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('permission', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_PERMISSIONS, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

export function _savePermission(referenceData) {
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!referenceData.id) {
            delete referenceData.id
            instance.post('permission/', {permission: referenceData})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_PERMISSION, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                })
                .catch(function (error) {
                    exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});

                });
        } else {
            instance.put('permission/' + referenceData.id, {permission: referenceData})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_PERMISSION, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                })
                .catch(function (error) {
                    exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        }
    }
}

export function _deletePermission(referenceData) {
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('permission/' + referenceData.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_PERMISSION, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
                dispatch({type: ActionTypes.LOADING, payload: false});
            });
    }
}
