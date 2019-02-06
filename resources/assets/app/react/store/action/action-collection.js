import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch all collection
 *
 * @param params
 * @returns dispatch
 */
export function _fetchAllCollection(params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('collection', {params: params})
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_COLLECTIONS, payload: response})
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
export function _saveCollection(data) {
    return dispatch => {
        dispatch({type: ActionTypes.ERROR, payload: ""})
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        if (!data.id) {
            delete data.id
            instance.post('collection', {collection: data})
                .then(function (response) {

                    dispatch({type: ActionTypes.SAVE_COLLECTION, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllCollection());
                })
                .catch(function (error) {
                    // exceptionHandler(error);
                    dispatch({type: ActionTypes.ERROR, payload: error.response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                });
        }
        else {
            instance.put('collection/' + data.id, {collection: data})
                .then(function (response) {
                    dispatch({type: ActionTypes.SAVE_COLLECTION, payload: response})
                    dispatch({type: ActionTypes.LOADING, payload: false});
                    dispatch(_fetchAllCollection());
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
export function _deleteCollection(data) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.delete('collection/' + data.id)
            .then(function (response) {
                dispatch({type: ActionTypes.DELETE_COLLECTION, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
                dispatch(_fetchAllCollection());
            })
            .catch(function (error) {
                // exceptionHandler(error);
            });
    }
}