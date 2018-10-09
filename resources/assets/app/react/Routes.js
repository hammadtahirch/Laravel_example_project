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
import ShopMenu from "./echo_system/pages/Shops/ShopMenu";
import ShopSettings from "./echo_system/pages/Shops/ShopSettings";

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
                            <Route exact path='/admin/shop/:id/time_slot' component={ShopTimeSlots}/>
                            <Route exact path='/admin/shop/:id/menu' component={ShopMenu}/>
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