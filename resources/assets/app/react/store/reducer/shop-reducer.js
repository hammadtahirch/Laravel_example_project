import ActionTypes from '../constant/constant';

const INITIAL_STATE = {
    fetch_shops: '',
    save_shop: '',
    delete_shop: ''

}

export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.FETCH_SHOPS:
            return ({
                ...state,
                fetch_shops: action.payload.data
            })
            break;
        case ActionTypes.SAVE_SHOP:
            return ({
                ...state,
                error: '',
                save_shop: action.payload.data
            })
            break;
        case ActionTypes.DELETE_SHOP:
            return ({
                ...state,
                delete_shop: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}