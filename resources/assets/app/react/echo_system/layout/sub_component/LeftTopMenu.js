import React, {Component} from 'react';
import {connect} from 'react-redux';
import {getSession} from "../../../store/helper/auth-helper";
import Config from "../../../store/config/app-constants";

class LeftTopMenu extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        let session = getSession('login');
        return (


            <nav className="classy-navbar" id="essenceNav">

                <a className="nav-brand"><img
                    src={require('../../../assets/img/core-img/logo.png')} alt=""/></a>
                <div className="classy-navbar-toggler">
                    <span className="navbarToggler"><span></span><span></span><span></span></span>
                </div>
                {
                    session !== null ?

                        < div className="classy-menu">

                            < div className="classycloseIcon">
                                <div className="cross-wrap"><span className="top"></span><span
                                    className="bottom"></span></div>
                            </div>

                            <div className="classynav">
                                <ul>
                                    <li><a href="dashboard">DashBoard</a></li>
                                    {/*<li className="megamenu-item"><a href="#">Shop</a>*/}
                                        {/*<div className="megamenu">*/}
                                            {/*<ul className="single-mega cn-col-4">*/}
                                                {/*<li className="title">Women's Collection</li>*/}
                                                {/*<li><a href="shop.html">Dresses</a></li>*/}
                                                {/*<li><a href="shop.html">Blouses &amp; Shirts</a></li>*/}
                                                {/*<li><a href="shop.html">T-shirts</a></li>*/}
                                                {/*<li><a href="shop.html">Rompers</a></li>*/}
                                                {/*<li><a href="shop.html">Bras &amp; Panties</a></li>*/}
                                            {/*</ul>*/}
                                            {/*<ul className="single-mega cn-col-4">*/}
                                                {/*<li className="title">Men's Collection</li>*/}
                                                {/*<li><a href="shop.html">T-Shirts</a></li>*/}
                                                {/*<li><a href="shop.html">Polo</a></li>*/}
                                                {/*<li><a href="shop.html">Shirts</a></li>*/}
                                                {/*<li><a href="shop.html">Jackets</a></li>*/}
                                                {/*<li><a href="shop.html">Trench</a></li>*/}
                                            {/*</ul>*/}
                                            {/*<ul className="single-mega cn-col-4">*/}
                                                {/*<li className="title">Kid's Collection</li>*/}
                                                {/*<li><a href="shop.html">Dresses</a></li>*/}
                                                {/*<li><a href="shop.html">Shirts</a></li>*/}
                                                {/*<li><a href="shop.html">T-shirts</a></li>*/}
                                                {/*<li><a href="shop.html">Jackets</a></li>*/}
                                                {/*<li><a href="shop.html">Trench</a></li>*/}
                                            {/*</ul>*/}
                                        {/*</div>*/}
                                        {/*<span className="dd-trigger"></span><span className="dd-arrow"></span></li>*/}
                                    <li className="cn-dropdown-item has-down pr12">
                                        <a href="#">Accounts</a>
                                        <ul className="dropdown">
                                            <li>
                                                <a href={Config.WEB_ADDRESS + "admin/user_management"}>User Management</a>
                                            </li>
                                            <li><a href={Config.WEB_ADDRESS + "admin/shop_management"}>Shop Management</a></li>
                                            <li><a href={Config.WEB_ADDRESS + "admin/collections"}>Collections</a></li>
                                            <li><a href={Config.WEB_ADDRESS + "admin/templates"}>Templates</a></li>

                                        </ul>
                                        <span className="dd-trigger"></span><span className="dd-arrow"></span></li>
                                    <li><a href="blog.html">Blog</a></li>

                                </ul>
                            </div>

                        </div> : ''
                }


            </nav>

        );
    }
}

function mapStateToProp(state) {
    return ({})
}

function mapDispatchToProp(dispatch) {
    return ({})
}

export default connect(mapStateToProp, mapDispatchToProp)(LeftTopMenu);