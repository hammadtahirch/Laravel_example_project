import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

export function _fetchAllShop(params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('shop', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_SHOPS, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

export function _saveShop(data) {
    let objectInstance = '';
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('shop', {shop: data})
                .then(function (response) {

                    dispatch({type: ActionTypes.SAVE_USER, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                })
                .catch(function (error) {
                    exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        } else {
            instance.put('shop/' + data.id, {shop: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_USER, payload: response})
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

export function _deleteShop(data) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('shop/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_USER, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}