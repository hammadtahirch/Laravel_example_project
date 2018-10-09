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
import {_fetchAllShop} from "../../../store/action/action-shop";
import {_fetchShopTimeSlot} from "../../../store/action/action-time-slot";


const queryString = require('query-string');

class ShopTimeSlots extends Component {

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
        // console.log(this.props.match.params);
        this.props.fetch_shop_time_slots(this.props.match.params);
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
        console.log(this.props.match);
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
                                        <h2>Time Slot</h2>
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
    return ({
        fetch_shop_time_slots: (params) => {
            dispatch(_fetchShopTimeSlot(params));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(ShopTimeSlots);
