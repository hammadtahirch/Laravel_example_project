import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment';
import {DatetimePickerTrigger} from 'rc-datetime-picker';

import 'react-toastify/dist/ReactToastify.css';
import {getSession} from "../../../store/helper/helper";
import history from "../../../History";
import Header from "../../layout/Header";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import ShopNav from "./_nav/ShopNav";


const queryString = require('query-string');

class ShopSettings extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);

        if (getSession('login') === null) {
            history.push('login');
        }

        this.state = {
            moment: moment(),
        };
        this.handleChange = this.handleChange.bind(this);
    }

    /**
     * componentWillMount [react default life cycle functions]
     */
    componentWillMount() {
    }

    /**
     * componentWillReceiveProps [react default life cycle functions]
     * @param NextProps
     */
    componentWillReceiveProps(NextProps) {
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {

    }

    /**
     * handleChange
     * @param var event
     */
    handleChange(moment) {
        debugger;
        this.setState({
            moment
        });
    }

    /**
     * render [DOM render ]
     */
    render() {
        const shortcuts = {
            'Today': moment(),
            'Yesterday': moment().subtract(1, 'days'),
            'Clear': ''
        };
        const modalStyle = {
            modal: {
                maxWidth: "500px",
            }
        }
        return (

            <div>
                <ToastContainer/>
                <Loading/>
                <Header/>

                <div className="single-blog-wrapper">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-3 col-md-3">
                                <div className="regular-page-content-wrapper left-menu mt-30 mb-30">
                                    <ShopNav params={this.props.match} />
                                </div>

                            </div>
                            <div className="col-9 col-md-9">
                                <div className="regular-page-content-wrapper mt-30 min-height">
                                    <div className="regular-page-text">
                                        <h2>Settings</h2>
                                        <DatetimePickerTrigger
                                            shortcuts={shortcuts}
                                            moment={this.state.moment}
                                            onChange={this.handleChange}
                                            showCalendarPicker={false}
                                            showTimePicker={true}>
                                            <input type="text" value={this.state.moment.format('YYYY-MM-DD HH:mm')}
                                                   readOnly/>
                                        </DatetimePickerTrigger>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>

                </div>
            </div>

        );
    }
}

/**
 * mapStateToProp
 * @param  state
 * @return states
 */
function mapStateToProp(state) {
    return ({
        error: state.account.error,
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({})
}

export default connect(mapStateToProp, mapDispatchToProp)(ShopSettings);
