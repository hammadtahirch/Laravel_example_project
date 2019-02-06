import account from './account-reducer';
import shop from './shop-reducer';
import setting from './setting-reducer';
import shop_time_slot from './shop-time-slot-reducer';
import collection from './collection-reducer';
import template from './email_templates-reducer';
import error from './error-reducer';
import {combineReducers} from 'redux';

/**
 *
 */
export default combineReducers({
    account: account,
    shop: shop,
    shop_time_slot: shop_time_slot,
    setting: setting,
    collection: collection,
    template: template,
    error: error
});