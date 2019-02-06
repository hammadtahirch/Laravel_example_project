import React, {Component} from 'react';
import store from "../../../store";

class ValidationErrors extends Component {

    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
    }

    /**
     *
     * @param props
     * @param is_error
     * @returns {*}
     */
    _validation(props) {
        if (props.statusCode === 401) {
            if (typeof props.validationErrors.error === "undefined") {
                return (<strong>Whoops! Sorry your are not valid user</strong>);
            } else {
                return (
                    <li>
                        {props.validationErrors.error.message}
                    </li>
                );
            }


        } else if (props.statusCode === 422) {
            debugger;
            let errorList = [];
            for (var item = 0; item <= (Object.keys(props.validationErrors.errors).length) - 1; item++) {
                errorList.push(
                    <li key={item}>{props.validationErrors.errors[Object.keys(props.validationErrors.errors)[item]]}</li>
                );
            }
            return errorList;
        }
        else if (props.statusCode === 500) {
            debugger;
            return (<strong>Whoops! Sorry {props.validationErrors.message}</strong>);
        }

    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {
        return (
            <div className="col-md-12 col-sm-12 col-xs-12 padding-0">
                <div className="alert alert-danger" role="alert">
                    <dl>
                        {this._validation(this.props)}
                    </dl>
                </div>
            </div>
        )
    }
}

export default ValidationErrors;
