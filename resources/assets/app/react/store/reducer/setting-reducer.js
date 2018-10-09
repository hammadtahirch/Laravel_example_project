import ActionTypes from '../constant/constant';

const INITIAL_STATE = {
    save_permission: '',
    fetch_permissions: '',
    delete_permission: ''
}

export default (state = INITIAL_STATE, action) => {
    switch (action.type) {
        case ActionTypes.FETCH_PERMISSIONS:
            return ({
                ...state,
                fetch_permissions: action.payload.data
            })
            break;
        case ActionTypes.SAVE_PERMISSION:
            return ({
                ...state,
                error: '',
                save_permission: action.payload.data
            })
            break;
        case ActionTypes.DELETE_PERMISSION:
            return ({
                ...state,
                delete_permission: action.payload.data
            })
            break;
        default:
            return state;
            break;
    }

}