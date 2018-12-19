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
import {_fetchShopTimeSlot, _saveShopTimeSlot} from "../../../store/action/action-time-slot";
import {getDayName} from "../../../store/helper/utill-helper";


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
            is_update: false,
            is_set_date: false,
            shop_time_slot: {
                id: "",
                deliver_start_time: "",
                delivery_end_time: "",
                pickup_start_time: "",
                pickup_end_time: "",

            },
            shop_set_close_date: {
                change_delivery_date: '',
                change_pickup_date: ''
            },
        };
        this.handleChange = this.handleChange.bind(this);
    }

    /**
     * componentWillMount [react default life cycle functions]
     */
    componentWillMount() {
        this.props.fetch_shop_time_slots(this.props.match.params.id);
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
     * handleInputChange
     */
    handleInputChange(event) {
        debugger;
        const {name, value} = event.target;
        const {shop_time_slot} = this.state;
        this.setState({
            shop_time_slot: {
                ...shop_time_slot,
                [name]: value
            }
        });
    }

    /**
     * handleEditTimeSlot
     */
    handleEditTimeSlot(timeSlot = null) {
        if (timeSlot === null) {
            this.setState({is_set_date: true});
        } else if (timeSlot !== null) {
            this.setState({
                shop_time_slot: {
                    id: timeSlot.id,
                    deliver_start_time: timeSlot.deliver_start_time,
                    delivery_end_time: timeSlot.delivery_end_time,
                    pickup_start_time: timeSlot.pickup_start_time,
                    pickup_end_time: timeSlot.pickup_end_time,

                },
            });
            this.setState({is_update: true});
        }

    }

    /**
     * handleSaveTimeSlot
     */
    handleSaveTimeSlot() {
        this.setState({is_update: false});
        this.props.save_shop_time_slot(this.props.match.params.id, this.state.shop_time_slot);
    }

    /**
     * handleSaveDateSlot
     */
    handleSaveDateSlot() {
        this.setState({is_update: false});
        this.props.save_shop_time_slot(this.props.match.params.id, this.state.shop_set_close_date);
    }

    /**
     * handleDeleteDateSlot
     */
    handleDeleteDateSlot(clue, slot) {
        if (clue === 'delivery') {

        }
        else if (clue === 'pickup') {
        }
    }

    /**
     * _timeSlotList
     */
    _timeSlotList() {
        if (this.props.time_slots !== '') {
            return this.props.time_slots.shop_time_slots.map((timeSlot, index) => {
                return (
                    <tr key={index}>
                        <td>{timeSlot.id}</td>
                        <td>{getDayName(timeSlot.day)}</td>
                        <td>{timeSlot.deliver_start_time} - {timeSlot.delivery_end_time}</td>
                        <td>{timeSlot.pickup_start_time} - {timeSlot.pickup_end_time}</td>
                        <td>
                            {timeSlot.change_delivery_date !== null ?
                                <span onClick={() => this.handleDeleteDateSlot('delivery', timeSlot)}>
                                    <i className="fa fa-minus-circle"></i> {timeSlot.change_delivery_date}
                                </span> :
                                <span>
                                    Delivery Date
                                </span>
                            }
                            &nbsp; - &nbsp;
                            {timeSlot.change_pickup_date !== null ?
                                <span onClick={() => this.handleDeleteDateSlot('pickup', timeSlot)}>
                                    <i className="fa fa-minus-circle"></i> timeSlot.change_pickup_date
                                </span> :
                                <span>
                                     PickUp Date
                                </span>
                            }
                        </td>
                        <td className='text-center'>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item"
                                   onClick={() => this.handleEditTimeSlot(timeSlot)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                            </div>
                        </td>
                    </tr>
                )
            })

        }
    }

    /**
     * render [DOM render ]
     */
    render() {
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
                                    <ShopNav params={this.props.match}/>
                                </div>

                            </div>
                            <div className="col-9 col-md-9">
                                <div className="regular-page-content-wrapper mt-30 min-height">
                                    <div className="regular-page-text">
                                        <h2>Time Slot</h2>
                                        {this.state.is_update &&
                                        <div className="checkout_details_area clearfix">
                                            <form>
                                                <pre><code>{JSON.stringify('')}</code></pre>
                                                <div className="row">
                                                    <div className="col-6 col-md-6">
                                                        <strong>Delivery</strong>
                                                        <div className="row">

                                                            <div className="col-md-6 mb-3">
                                                                <input type="text" className="form-control"
                                                                       name="deliver_start_time"
                                                                       placeholder="Opening Time"
                                                                       value={this.state.shop_time_slot.deliver_start_time}
                                                                       onChange={(event) => this.handleInputChange(event)}/>
                                                            </div>
                                                            <div className="col-md-6 mb-3">
                                                                <input type="text" className="form-control"
                                                                       name="delivery_end_time"
                                                                       placeholder="Closing Time"
                                                                       value={this.state.shop_time_slot.delivery_end_time}
                                                                       onChange={(event) => this.handleInputChange(event)}/>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div className="col-6 col-md-6">
                                                        <strong>Pick up</strong>
                                                        <div className="row">

                                                            <div className="col-md-6 mb-3">
                                                                <input type="text" className="form-control"
                                                                       name="pickup_start_time"
                                                                       placeholder="Opening Timing"
                                                                       value={this.state.shop_time_slot.pickup_start_time}
                                                                       onChange={(event) => this.handleInputChange(event)}/>
                                                            </div>
                                                            <div className="col-md-6 mb-3">
                                                                <input type="text" className="form-control"
                                                                       name="pickup_end_time"
                                                                       placeholder="Closing Time"
                                                                       value={this.state.shop_time_slot.pickup_end_time}
                                                                       onChange={(event) => this.handleInputChange(event)}/>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div className="col-md-12 mb-3">
                                                        <button type="button"
                                                                className="btn btn-outline-dark font-14 pull-right"
                                                                onClick={() => this.handleSaveTimeSlot()}>Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        }

                                        {this.state.is_set_date &&
                                        <div className="checkout_details_area clearfix">
                                            <form>
                                                <pre><code>{JSON.stringify('')}</code></pre>
                                                <div className="row">

                                                    <div className="col-md-4 mb-3">
                                                        <strong>Delivery Close Date</strong>
                                                        <DatetimePickerTrigger
                                                            shortcuts={{}}
                                                            moment={this.state.moment}
                                                            showCalendarPicker={true}
                                                            onChange={this.handleChange}
                                                            showTimePicker={false}>
                                                            <input type="text"
                                                                   className="form-control"
                                                                   value={this.state.moment.format("YYYY-MM-DD")}
                                                                   readOnly
                                                            />
                                                        </DatetimePickerTrigger>
                                                    </div>
                                                    <div className="col-md-4 mb-3">
                                                        <strong>PickUp Close Date</strong>
                                                        <DatetimePickerTrigger
                                                            shortcuts={{}}
                                                            moment={this.state.moment}
                                                            showCalendarPicker={true}
                                                            onChange={this.handleChange}
                                                            showTimePicker={false}>
                                                            <input type="text"
                                                                   className="form-control"
                                                                   value={this.state.moment.format("YYYY-MM-DD")}
                                                                   readOnly
                                                            />
                                                        </DatetimePickerTrigger>
                                                    </div>
                                                    <div className="col-md-12 mb-3">
                                                        <button type="button"
                                                                className="btn btn-outline-dark font-14"
                                                                onClick={() => this.handleSaveTimeSlot()}>Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        }
                                        <button className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                onClick={() => this.handleEditTimeSlot()}>
                                            Set Off Date
                                        </button>
                                        <table className="table table-bordered mb-30">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Day</th>
                                                <th>Delivery Timing</th>
                                                <th>PickUp Timing</th>
                                                <th>Close Delivery - Close pickup</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {this._timeSlotList()}

                                            </tbody>
                                        </table>
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
        time_slots: state.shop_time_slot.fetch_shop_time_slot,
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
        fetch_shop_time_slots: (shop_id) => {
            dispatch(_fetchShopTimeSlot(shop_id));
        },
        save_shop_time_slot: (shop_id, params) => {
            dispatch(_saveShopTimeSlot(shop_id, params));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(ShopTimeSlots);
