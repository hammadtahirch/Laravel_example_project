import React, {Component} from 'react';
import {Route, Router, Switch} from 'react-router-dom';
import history from './History';

import DefaultLayout from '../react/echo_system/layout/DefaultLayout';
import Login from './echo_system/pages/Accounts/Login';
import Error404 from "./echo_system/pages/Errors/Error404";
import Dashboard from "./echo_system/pages/Accounts/Dashboard";
import UserManagement from "./echo_system/pages/Accounts/UserManagement";
import ShopManagement from "./echo_system/pages/Shops/ShopManagement";
import RolePermission from "./echo_system/pages/Settings/RolePermission";
import Error403 from "./echo_system/pages/Errors/Error403";
import Error500 from "./echo_system/pages/Errors/Error500";
import ShopTimeSlots from "./echo_system/pages/Shops/ShopTimeSlots";
import ShopProducts from "./echo_system/pages/Shops/ShopProducts";
import ShopSettings from "./echo_system/pages/Shops/ShopSettings";
import Collections from "./echo_system/pages/Settings/Collections";
import EmailTemplates from "./echo_system/pages/Settings/EmailTemplates";
import CreateOrUpdateProduct from "./echo_system/pages/Shops/CreateOrUpdateProduct";
import CreateUpdateProductVariance from "./echo_system/pages/Shops/CreateUpdateProductVariance";
import CreateUpdateProductVarianceOption from "./echo_system/pages/Shops/CreateUpdateProductVarianceOption";

class Routes extends Component {
    render() {
        return (
            <Router history={history}>
                <div>
                    <DefaultLayout>
                        <Switch>
                            <Route exact path='/admin' component={Login}/>
                            <Route exact path='/admin/login' component={Login}/>
                            <Route exact path='/admin/dashboard' component={Dashboard}/>
                            <Route exact path='/admin/user_management' component={UserManagement}/>
                            <Route exact path='/admin/shop_management' component={ShopManagement}/>
                            <Route exact path='/admin/collections' component={Collections}/>
                            <Route exact path='/admin/templates' component={EmailTemplates}/>
                            <Route exact path='/admin/shop/:id/time_slot' component={ShopTimeSlots}/>
                            <Route exact path='/admin/shop/:id/products' component={ShopProducts}/>

                            <Route exact path='/admin/shop/:id/create_or_update/:product_id?'
                                   component={CreateOrUpdateProduct}/>
                            <Route exact path='/admin/shop/:id/product/:product_id/create_update_product_variance'
                                   component={CreateUpdateProductVariance}/>
                            <Route exact path='/admin/shop/:id/variance/:variance_id/create_update_variance_option'
                                   component={CreateUpdateProductVarianceOption}/>

                            <Route exact path='/admin/shop/:id/settings' component={ShopSettings}/>

                            <Route exact path='/admin/role_permission' component={RolePermission}/>

                            <Route exact path='/admin/403' component={Error403}/>
                            <Route exact path='/admin/404' component={Error404}/>
                            <Route exact path='/admin/500' component={Error500}/>
                            <Route component={Error404}/>
                        </Switch>
                    </DefaultLayout>
                </div>
            </Router>
        );
    }
}

export default Routes;