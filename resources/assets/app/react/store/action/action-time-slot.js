import {_setHeaders, exceptionHandler, setSession} from "../helper/auth-helper";
import ActionTypes from "../constant/constant";

export function _fetchShopTimeSlot(params) {
    return dispatch => {
        dispatch({type: ActionTypes.LOADING, payload: true});
        const instance = _setHeaders();
        debugger;
        instance.get('shop/' + params.id + "/time_slot")
            .then(function (response) {
                dispatch({type: ActionTypes.FETCH_SHOPS_TIME_SLOT, payload: response})
                dispatch({type: ActionTypes.LOADING, payload: false});
            })
            .catch(function (error) {
                exceptionHandler(error);
            });
    }
}