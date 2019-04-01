import React, {Component} from 'react';
import {connect} from 'react-redux';

class SuggestionInput extends Component {
    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
        this.state = {
            input: props.value,
        }
        this.handleOnClick = this.handleOnClick.bind(this);
    }

    /**
     * componentWillReceiveProps [react default life cycle functions]
     */
    componentWillReceiveProps(nextProps, nextState) {
        if (nextProps.suggestions === '') {
            this.setState({
                input: nextProps.value
            });
        }

    }

    /**
     *
     * @param e
     */
    handleOnKeyUp(e) {
        if (e.target.value.length >= 5) {
            if (typeof this.props.onKeyUp === 'function') {
                this.props.onKeyUp(e)
            }
        }
    }

    /**
     *
     * @param e
     */
    handleOnChange(e) {
        this.setState({
            input: e.target.value
        });
    }

    /**
     *
     * @param value
     * @param key
     */
    handleOnClick(value, key) {
        this.setState({input: value[key]});
        if (typeof this.props.onClick === 'function') {
            this.props.onClick(value)
        }
    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {
        console.log(this.props);
        return (
            <div className="col-md-12 mb-3">
                <label>{this.props.lable}<span>*</span></label>
                <input type="text" className="form-control" name="input"
                       onKeyUp={(e) => this.handleOnKeyUp(e)}
                       value={this.state.input}
                       onChange={(e) => this.handleOnChange(e)}
                       placeholder={this.props.placeholder}
                />
                {this.props.suggestions !== '' &&
                <ul className="list-group user-autoCompelete">
                    {this.props.suggestions.map((value, index) => {
                        return <li className="list-group-item"
                                   onClick={() => this.handleOnClick(value, this.props.displayKey)}
                                   key={index}>{value[this.props.displayKey]}</li>
                    })}
                </ul>
                }
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

export default connect(mapStateToProp, mapDispatchToProp)(SuggestionInput);
