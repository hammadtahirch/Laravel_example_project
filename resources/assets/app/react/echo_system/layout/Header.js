import React, {Component} from 'react';
import {connect} from 'react-redux';
import store from '../../../react/store/index'
import {_signOutAction} from "../../store/action/action-acounts";
import RightTopMenu from "./sub_component/RightTopMenu";
import LeftTopMenu from "./sub_component/LeftTopMenu";

class Header extends Component {

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
        return (
            <div>
                <header className="header_area">
                    <div
                        className="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
                        <LeftTopMenu/>
                        <RightTopMenu/>
                    </div>
                </header>
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

export default connect(mapStateToProp, mapDispatchToProp)(Header);