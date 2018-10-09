import ActionTypes from '../constant/constant';

const INITIAL_STATE = {
    fetch_shop_time_slot: '',

}

export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_SHOPS_TIME_SLOT:
            return ({
                ...state,
                fetch_shop_time_slot: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}