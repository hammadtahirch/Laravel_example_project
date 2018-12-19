import account from './account-reducer';
import shop from './shop-reducer';
import setting from './setting-reducer';
import shop_time_slot from './shop-time-slot-reducer';
import {combineReducers} from 'redux';

export default combineReducers({
    account: account,
    shop: shop,
    shop_time_slot: shop_time_slot,
    setting: setting
});