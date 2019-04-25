import React, {Component} from 'react';
import {connect} from 'react-redux';
import {getSession} from "../../../store/helper/auth-helper";
import store from '../../../store/index'
import {_signOutAction} from "../../../store/action/action-acounts";
import Config from "../../../store/config/app-constants";

class RightTopMenu extends Component {

    constructor(props) {
        super(props);
        this.handleSignOut = this.handleSignOut.bind(this);
    }

    /**
     * this function is responsible for destroy sessions
     * @param  {[type]} event [description]
     * @return {[type]}       [description]
     */
    handleSignOut(event) {
        store.dispatch(_signOutAction());
    }

    render() {
        let session = getSession('login');

        return (
            <div className="header-meta d-flex clearfix justify-content-end">
                {session !== null ? <div className="user-login-info">
                    <a href="" className="dropdown-toggle" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <img src={require('../../../assets/img/core-img/user.svg')} alt=""/>
                    </a>
                    <div className="dropdown-menu">
                        <a className="dropdown-item manu_dropdown_a">Profile</a>
                        <div className="dropdown-divider"></div>
                        <a className="dropdown-item manu_dropdown_a"
                           href={Config.WEB_ADDRESS + "admin/role_permission"}>Settings</a>
                        <div className="dropdown-divider"></div>
                        <a className="dropdown-item manu_dropdown_a" onClick={this.handleSignOut}>Sign Out</a>
                    </div>
                </div> : ''}
            </div>
        );
    }
}

function mapStateToProp(state) {
    return ({})
}

function mapDispatchToProp(dispatch) {
    return ({})
}

export default connect(mapStateToProp, mapDispatchToProp)(RightTopMenu);