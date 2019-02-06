import ActionTypes from '../constant/constant';

/**
 *
 * @type {{user: string, fetch_users: string, fetch_roles: string, delete_user: string, save_user: string, error: string, is_loading: boolean}}
 */
const INITIAL_STATE = {
    user: '',
    fetch_users: '',
    fetch_roles: '',
    delete_user: '',
    save_user: '',
    error: '',
    is_loading: false,
}

/**
 *
 * @param state
 * @param action
 * @returns {{user: string, fetch_users: string, fetch_roles: string, delete_user: string, save_user: string, error: string, is_loading: boolean}}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {
        case ActionTypes.FETCH_ROLES:
            return ({
                ...state,
                fetch_roles: action.payload.data
            })
            break;
        case ActionTypes.FETCH_USERS:
            return ({
                ...state,
                fetch_users: action.payload.data,
                save_user: '',
                delete_user: '',
                error: ''
            })
            break;

        case ActionTypes.SAVE_USER:
            return ({
                ...state,
                error: '',
                save_user: action.payload.data,

            })
            break;
        case ActionTypes.DELETE_USER:
            return ({
                ...state,
                delete_user: action.payload.data
            })
            break;
        case ActionTypes.LOGIN:
            return ({
                ...state,
                error: '',
                user: action.payload.data
            })
            break;
        case ActionTypes.SIGN_OUT:
            return ({
                ...state,
                error: '',
                user: action.payload.data
            })
            break;
        case ActionTypes.LOADING:
            return ({
                ...state,
                is_loading: action.payload
            })
            break;
        case ActionTypes.UNLOADING:
            return ({
                ...state,
                is_loading: action.payload
            })
            break;
        default:
            return state;
            break;
    }

}