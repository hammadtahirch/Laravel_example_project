import React, { Component } from 'react';
import { connect } from 'react-redux';
import { _signInAction } from '../../../store/action/action-acounts';

class Error500 extends Component {
    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {

        return (
            <div className="single-blog-wrapper">
                <div className="container">
                    <div className="row justify-content-center space-bottom-40">

                        <div className="col-12 col-md-5">
                            <div className="checkout_details_area mt-50 clearfix">

                                <div className="unicorn_black_line"></div>

                                <div className="container">

                                    <div className="four-oh-four"><h1>500 Error</h1></div>

                                    <div className="warning">
                                        <a className={"btn btn-outline-dark font-14"} href="javascript:history.back()">Please go back to the previous page</a>
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

export default connect(mapStateToProp, mapDispatchToProp)(Error500);
