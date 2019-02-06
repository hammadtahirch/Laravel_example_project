import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

/**
 * fetch shop time slot
 *
 * @param shop_id
 * @returns dispatch
 */
export function _fetchShopTimeSlot(shop_id) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.get('shop/' + shop_id + "/time_slot")
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_SHOPS_TIME_SLOT, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

/**
 * save shop time slot
 *
 * @param shop_id
 * @param params
 * @returns dispatch
 */
export function _saveShopTimeSlot(shop_id, params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.put('shop/' + shop_id + "/time_slot/" + params.id, {shop_time_slot: params})
            .then(function (response) {
                dispatch({type: ActionTypes.SAVE_SHOPS_TIME_SLOT, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}

/**
 *save shop date slot
 *
 * @param shop_id
 * @param params
 * @returns dispatch
 */
export function _saveShopDateSlot(shop_id, params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        instance.put('shop/' + shop_id + "/time_slot/", {shop_time_slot: params})
            .then(function (response) {
                dispatch({type: ActionTypes.SAVE_SHOPS_TIME_SLOT, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}