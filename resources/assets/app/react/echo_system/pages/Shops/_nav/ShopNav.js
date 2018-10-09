import React, {Component} from 'react';
import {connect} from 'react-redux';
import Config from "../../../../store/config/app-constants";

const queryString = require('query-string');

class ShopNav extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);
        this.state = {
            segment: props.params
        }
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
     * render [DOM render ]
     */
    render() {
        let _activeTimeSlot = '';
        let _activeMenu = '';
        let _activeSettings = '';
        if (this.state.segment.url.includes('time_slot')) {
            _activeTimeSlot = " active";
        } else if (this.state.segment.url.includes('menu')) {
            _activeMenu = " active"
        } else if (this.state.segment.url.includes('settings')) {
            _activeSettings = " active"
        }
        return (

            <div>
                <div className="nav flex-column nav-pills">

                    <a className={"nav-link" + _activeMenu}
                       href={Config.WEB_ADDRESS + "admin/shop/" + this.state.segment.params.id + "/menu"}>
                        <i className="fa fa-bars"></i> Menu
                    </a>
                    <a className={"nav-link" + _activeTimeSlot}
                       href={Config.WEB_ADDRESS + "admin/shop/" + this.state.segment.params.id + "/time_slot"}>
                        <i className="fa fa-clock-o"></i> Time Settings
                    </a>
                    <a className={"nav-link" + _activeSettings}
                       href={Config.WEB_ADDRESS + "admin/shop/" + this.state.segment.params.id + "/settings"}>
                        <i className="fa fa-cogs"></i> Settings
                    </a>
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
    return ({})
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({})
}

export default connect(mapStateToProp, mapDispatchToProp)(ShopNav);
