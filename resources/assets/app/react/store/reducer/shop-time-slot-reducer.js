import ActionTypes from '../constant/constant';

/**
 *
 * @type {{fetch_shop_time_slot: string, save_shop_time_slot: string}}
 */
const INITIAL_STATE = {
    fetch_shop_time_slot: '',
    save_shop_time_slot: '',

}

/**
 *
 * @param state
 * @param action
 * @returns {{fetch_shop_time_slot: string, save_shop_time_slot: string}}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_SHOPS_TIME_SLOT:
            return ({
                ...state,
                fetch_shop_time_slot: action.payload.data
            })
            break;
            case ActionTypes.SAVE_SHOPS_TIME_SLOT:
            return ({
                ...state,
                save_shop_time_slot: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}