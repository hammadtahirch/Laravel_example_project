import React, {Component} from 'react';
import {connect} from 'react-redux';

import {getSession} from "../../../store/helper/auth-helper";
import history from "../../../History";
import Header from "../../layout/Header";

class Dashboard extends Component {

    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
        if (getSession('login') === null) {
            history.push('login');
        }
    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {
        return (
            <div>
                <Header/>
                <div className="single-blog-wrapper">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-12 col-md-12">
                                <div className="regular-page-content-wrapper clear-10">
                                    <div className="regular-page-text mb-15">
                                        <div className="card">
                                            <div className="card-body">
                                                <h5 className="card-title">DashBoard</h5>
                                                <p>Mauris viverra cursus ante laoreet eleifend. Donec vel fringilla
                                                    ante. Aenean finibus velit id urna vehicula, nec maximus est
                                                    sollicitudin. Praesent at tempus lectus, eleifend blandit felis.
                                                    Fusce augue arcu, consequat a nisl aliquet, consectetur elementum
                                                    turpis. Donec iaculis lobortis nisl, et viverra risus imperdiet eu.
                                                    Etiam mollis posuere elit non sagittis. Lorem ipsum dolor sit amet,
                                                    consectetur adipiscing elit. Nunc quis arcu a magna sodales
                                                    venenatis. Integer non diam sit amet magna luctus mollis ac eu nisi.
                                                    In accumsan tellus ut dapibus blandit.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

/**
 * [mapStateToProp description]
 * @param  {[type]} state [description]
 * @return {[type]}       [description]
 */
function mapStateToProp(state) {
    return ({})
}

/**
 * [mapDispatchToProp description]
 * @param  {[type]} dispatch [description]
 * @return {[type]}          [description]
 */
function mapDispatchToProp(dispatch) {
    return ({})
}

export default connect(mapStateToProp, mapDispatchToProp)(Dashboard);
