import React, {Component} from 'react';
import {connect} from 'react-redux';

class Loading extends Component {

    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
    }

    componentWillMount() {
    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {
        return (
            <div className={(this.props.is_loading ? "attach-progress " : "detached-progress")}>
                <div className="loader"></div>
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
    return ({
        is_loading: state.account.is_loading
    })
}

/**
 * [mapDispatchToProp description]
 * @param  {[type]} dispatch [description]
 * @return {[type]}          [description]
 */
function mapDispatchToProp(dispatch) {
    return ({})
}

export default connect(mapStateToProp, mapDispatchToProp)(Loading);
