import ActionTypes from '../constant/constant';

/**
 *
 * @type {{user: string, fetch_users: string, fetch_roles: string, delete_user: string, save_user: string, error: string, is_loading: boolean}}
 */
const INITIAL_STATE = {
    error: '',
}

/**
 *
 * @param state
 * @param action
 * @returns {{user: string, fetch_users: string, fetch_roles: string, delete_user: string, save_user: string, error: string, is_loading: boolean}}
 */
export default (state = INITIAL_STATE, action) => {
    switch (action.type) {

        case ActionTypes.ERROR:
            return ({
                ...state,
                error: action.payload
            })
            break;
        default:
            return state;
            break;
    }

}