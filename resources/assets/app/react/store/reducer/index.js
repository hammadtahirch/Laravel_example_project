import account from './account-reducer';
import shop from './shop-reducer';
import setting from './setting-reducer';
import {combineReducers} from 'redux';

export default combineReducers({
    account: account,
    shop: shop,
    setting: setting
});