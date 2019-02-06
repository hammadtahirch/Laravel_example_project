import React, {Component} from 'react';
import {connect} from 'react-redux';

class Switch extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            isChecked: this.props.isChecked
        }

    }

    componentWillMount() {
        this.setState({isChecked: this.props.isChecked});
    }


    render() {

        return (
            <div className="switch-container">
                <label>
                    <input ref="switch" checked={this.state.isChecked} onChange={(e) => this._handleChange(e)}
                           className="switch form-control"
                           type="checkbox"/>
                    <div>

                        <div></div>
                    </div>
                </label>
            </div>
        );
    }


    _handleChange(e) {
        debugger;
        this.setState({isChecked: !this.state.isChecked});
        if (typeof this.props.handleSwitch === 'function') {
            this.props.handleSwitch(!this.state.isChecked)
        }
    }

}

export default Switch;
