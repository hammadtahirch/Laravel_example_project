import React, {Component} from 'react';
import {connect} from 'react-redux';
import {_signInAction} from '../../../store/action/action-acounts';
import history from '../../../History';
import {getSession} from "../../../store/helper/auth-helper";
import Header from "../../layout/Header";
import Loading from "../sub_components/Loading";
import ValidationErrors from "../sub_components/ValidationErrors";

class Login extends Component {
    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
        if (getSession('login') !== null) {
            history.push('dashboard');
        }
        this.state = {
            submitted: false,
            user: {
                email: '',
                password: ''
            }
        };

        this.handleLogin = this.handleLogin.bind(this);
        this.handleChange = this.handleChange.bind(this);
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {

    }

    /**
     * [handleChange description]
     * @param  {[type]} event [description]
     * @return {[type]}       [description]
     */
    handleChange(event) {
        const {name, value} = event.target;
        const {user} = this.state;
        this.setState({
            user: {
                ...user,
                [name]: value
            }
        });
    }

    /**
     * [handleLogin description]
     * @return {[type]} [description]
     */
    handleLogin() {
        this.props.login(this.state);
    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {
        return (
            <div>
                <Header/>
                <Loading/>
                <div className="single-blog-wrapper">
                    <div className="container">
                        <div className="row justify-content-center space-bottom-40">

                            <div className="col-12 col-md-5">
                                <div className="checkout_details_area mt-50 clearfix">

                                    <div className="cart-page-heading mb-30">
                                        <h5>Sign in</h5>
                                    </div>

                                    <form action="#" method="post">
                                        {(this.props.error !== "") &&
                                        <ValidationErrors validationErrors={this.props.error.data}
                                                          statusCode={this.props.error.status}/>
                                        }
                                        <div className="row">

                                            <div className="col-12 mb-3">
                                                <label>Email <span>*</span></label>
                                                <input type="text" className="form-control" id="email" name="email"
                                                       value={this.state.user.email}
                                                       placeholder="Enter your email Or phone number"
                                                       onChange={this.handleChange}/>
                                            </div>

                                            <div className="col-12 mb-3">
                                                <label>Password <span>*</span></label>
                                                <input type="password" className="form-control mb-3" id="password"
                                                       name="password" value={this.state.user.password}
                                                       placeholder="Enter your password" onChange={this.handleChange}/>
                                            </div>
                                        </div>
                                        <button type="button" onClick={this.handleLogin}
                                                className="btn btn-outline-dark font-14">Sign In
                                        </button>
                                    </form>
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
    return ({
        error: state.error.error,
    })
}

/**
 * [mapDispatchToProp description]
 * @param  {[type]} dispatch [description]
 * @return {[type]}          [description]
 */
function mapDispatchToProp(dispatch) {
    return ({
        login: (data) => {
            dispatch(_signInAction(data));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(Login);
