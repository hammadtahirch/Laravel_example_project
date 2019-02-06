import React, {Component} from 'react';
import {connect} from 'react-redux';

import {getSession} from "../../../store/helper/auth-helper";
import history from "../../../History";
import Header from "../../layout/Header";
import Pagination from "../sub_components/Pagination";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {_fetchAllRoles, _fetchAllUser, _saveUser, _deleteUser} from "../../../store/action/action-acounts";
import Modal from "react-responsive-modal";
import ValidationErrors from "../sub_components/ValidationErrors";
import ActionTypes from "../../../store/constant/constant";
import store from "../../../store";
import classNames from 'classnames'
import Dropzone from "react-dropzone";

const queryString = require('query-string');

class UserManagement extends Component {

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
            modal: {
                show: false,
            },
            alert: {
                show: false,
                detail: {
                    _is_confirm: false,
                    _purpose: null
                },
            },
            event: '',
            user: {
                id: '',
                first_name: '',
                last_name: '',
                email: '',
                phone_number: '',
                role_id: '',
                status: '',
                dataUrl: ''

            },
            filter: {
                filterName: '',
                filterValue: ''
            },
            status: [
                {
                    name: 'Active',
                    value: true
                },
                {
                    name: 'Inactive',
                    value: false
                }
            ],
            error: ''

        };
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
        this.props.fetch_user_list(this._builtQuery());
        this.props.fetch_roles_list();
    }

    /**
     * handle drop zone drag and drop event
     *
     * @param e
     */
    onDrop(e) {
        e.forEach(file => {
            const reader = new FileReader();
            reader.onload = () => {
                const fileAsBinaryString = reader.result;
                const {user} = this.state;
                this.setState({
                    user: {
                        ...user,
                        dataUrl: fileAsBinaryString
                    }
                });
            };

            reader.onabort = () => console.log('file reading was aborted');
            reader.onerror = () => console.log('file reading has failed');
            reader.readAsDataURL(file);
        })
    }

    /**
     * _builtQuery
     */
    _builtQuery() {

        let fill = {};
        if (this.state.filter.filterName !== '' && this.state.filter.filterValue !== '') {

            fill[this.state.filter.filterName] = this.state.filter.filterValue
            return queryString.parse(location.search + queryString.stringify(fill))
        }
        else {
            return queryString.parse(location.search)
        }
    }

    /**
     * handleChange
     * @param var event
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
     * handleFilter
     * @param var event
     */
    handleFilter(event) {
        const {name, value} = event.target;
        const {filter} = this.state;
        this.setState({
            filter: {
                ...filter,
                [name]: value
            }
        });
    }

    /**
     * handleSearch
     */
    handleSearch() {
        this.props.fetch_user_list(this._builtQuery());
    }

    /**
     * handleIsModelOpen
     * @param var _isOpen
     */
    handleIsModelOpen(_isOpen) {
        if (_isOpen === true) {
            this.setState({modal: {show: true}});
        } else if (_isOpen === false) {
            this.setState({modal: {show: false}});
            this.setState(
                {
                    user: {
                        id: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone_number: '',
                        role_id: '',
                        status: '',
                        dataUrl: "",
                    },
                    error: ''
                }
            );
            store.dispatch({type: ActionTypes.ERROR, payload: ''})
        }
    }

    /**
     * handleDeleteUser
     * @param var _isOpen
     * @param object user
     * @param var is_confirm
     */
    handleDeleteUser(_isOpen, user = null, is_confirm = false) {
        if (user !== null) {
            this.setState({
                user: {
                    id: user.id,
                    first_name: user.name.split(' ')[0],
                    last_name: user.name.split(' ')[0],
                    email: user.email,
                    phone_number: user.phone_number,
                    role_id: user.role_id,
                    status: user.status,
                }
            });
        }
        if (is_confirm !== false) {
            this.props.delete_user(this.state.user);
        }
        if (_isOpen === true) {
            this.setState({alert: {show: true}});
        } else if (_isOpen === false) {
            this.setState({alert: {show: false}});
        }
    }

    /**
     * handleEditUser
     * @param Object user
     */
    handleEditUser(user) {
        this.setState(
            {
                user: {
                    id: user.id,
                    first_name: user.name.split(' ')[0],
                    last_name: user.name.split(' ')[1],
                    email: user.email,
                    phone_number: user.phone_number,
                    role_id: user.role_id,
                    status: user.status,
                }
            }
        );
        this.handleIsModelOpen(true);
    }

    /**
     * handleModalSave
     */
    handleModalSave(user) {
        this.props.save_user(user);
    }

    /**
     * _userList
     */
    _userList() {
        if (this.props.fetch_user_props !== '') {
            return this.props.fetch_user_props.users.map((user, index) => {
                return (
                    <tr key={index}>
                        <td>{user.name}</td>
                        <td>{user.email}</td>
                        <td>{user.role.name}</td>
                        <td>{user.phone_number}</td>
                        <td className='text-center'>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item" onClick={() => this.handleEditUser(user)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item" onClick={() => this.handleDeleteUser(true, user)}><i
                                    className='fa fa-trash'></i> Delete</a>
                            </div>
                        </td>
                    </tr>
                )
            })

        }
    }

    /**
     * _roleList
     */
    _roleList() {
        if (this.props.fetch_role_props !== '') {
            return this.props.fetch_role_props.roles.map(function (role, i) {
                return <option
                    value={role.id} key={i}>
                    {role.name}
                </option>;
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

        {
            (this.props.save_user_props !== "") && toast.success("Wow! User Save Successfully.")
        }
        return (

            <div>
                <ToastContainer/>
                <Loading/>
                <Header/>


                <div className="single-blog-wrapper">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-12 col-md-12">
                                <div className="regular-page-content-wrapper clear-10">
                                    <div className="regular-page-text mb-15">
                                        <div className="card">
                                            <div className="card-body">
                                                <h5 className="card-title">User Management</h5>
                                                <button className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                        onClick={() => this.handleIsModelOpen(true)}>Create User
                                                </button>
                                                <div className="clear-5"></div>
                                                <form className="mb-30">
                                                    <div className="row ">
                                                        <div className="col-md-2">
                                                            <select className="form-control" name="filterName"
                                                                    onChange={(e) => this.handleFilter(e)}>
                                                                <option value='filter_by'>Filter By</option>
                                                                <option value='name'>Name</option>
                                                                <option value='email'>Email</option>
                                                                <option value='phone_number'>Phone Number</option>
                                                            </select>
                                                        </div>
                                                        <div className="col-md-4">
                                                            <input type="text" className="form-control"
                                                                   name="filterValue"
                                                                   onChange={(e) => this.handleFilter(e)}
                                                                   placeholder="Please Enter Query"/>
                                                        </div>
                                                        <div className="col-md-4">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14"
                                                                    onClick={(e) => this.handleSearch(e)}>
                                                                Search
                                                            </button>
                                                        </div>
                                                    </div>

                                                </form>

                                                <table className="table table-bordered mb-30">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Phone Number</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._userList()}

                                                    </tbody>
                                                </table>
                                                {this.props.fetch_user_props.meta && this.props.fetch_user_props.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetch_user_props.meta}
                                                            url={location.pathname}/>
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Modal
                    open={this.state.modal.show}
                    onClose={() => this.handleIsModelOpen(false)}
                    closeOnEsc={false}
                    closeOnOverlayClick={false}
                    styles={modalStyle}>

                    <div className="container">
                        <div className="row">
                            <div className="col-12 col-md-12">
                                <div className="checkout_details_area mt-50 clearfix">

                                    <div className="cart-page-heading mb-30">
                                        {
                                            (this.state.user.id == '' || this.state.user.id == null) ?
                                                <h5>Add User</h5> : <h5>Update User</h5>
                                        }
                                    </div>

                                    <form>
                                        <div className="row ">
                                            {(this.props.error !== "") &&
                                            <ValidationErrors validationErrors={this.props.error.data}
                                                              statusCode={this.props.error.status}/>
                                            }
                                            <div className="col-md-12 mb-2">
                                                <Dropzone onDrop={(e) => this.onDrop(e)}>
                                                    {({getRootProps, getInputProps, isDragActive}) => {
                                                        return (
                                                            <div
                                                                {...getRootProps()}
                                                                className={classNames('dropzone model-drop-zone', {'dropzone--isActive': isDragActive})}>
                                                                <input
                                                                    className="form-control" {...getInputProps()} />
                                                                {
                                                                    isDragActive ?
                                                                        <span>Drop here</span> :
                                                                        <span>Try dropping some files here,</span>
                                                                }
                                                            </div>
                                                        )
                                                    }}
                                                </Dropzone>
                                                {this.state.user.dataUrl !== "" &&
                                                <p>YaHu! the image selected.</p>}
                                            </div>
                                            <div className="col-md-6 mb-3">
                                                <label>First Name <span>*</span></label>
                                                <input type="text" className="form-control" name="first_name"
                                                       value={this.state.user.first_name}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-6 mb-3">
                                                <label>Last Name <span>*</span></label>
                                                <input type="text" className="form-control" name="last_name"
                                                       value={this.state.user.last_name}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-12 mb-3">
                                                <label>Email <span>*</span></label>
                                                <input type="text" className="form-control" name="email"
                                                       value={this.state.user.email}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-12 mb-3">
                                                <label>Phone Number <span>*</span></label>
                                                <input type="text" className="form-control" name="phone_number"
                                                       value={this.state.user.phone_number}
                                                       onChange={(e) => this.handleChange(e)}/>
                                            </div>
                                            <div className="col-md-12 mb-3">
                                                <label>Choose Role <span>*</span></label>
                                                <select className="form-control" name="role_id"
                                                        value={this.state.user.role_id}
                                                        onChange={(e) => this.handleChange(e)}>
                                                    <option value=''>Choose Role</option>
                                                    {this._roleList()}
                                                </select>
                                            </div>
                                            <div className="col-md-12 mb-3">

                                                <label>Status <span>*</span></label>
                                                <select className="form-control" name="status"
                                                        onChange={(e) => this.handleChange(e)}
                                                        value={this.state.user.status}>
                                                    <option value=''>Choose Status</option>
                                                    {this.state.status.map(function (status, i) {
                                                        return <option
                                                            value={status.value} key={i}>
                                                            {status.name}
                                                        </option>;
                                                    })}
                                                </select>
                                            </div>
                                            <div className="col-md-12 mb-3">
                                                <button type="button"
                                                        className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                        onClick={() => this.handleModalSave(this.state.user)}>
                                                    {
                                                        (this.state.user.id == '' || this.state.user.id == null) ? 'Add' : 'Update'
                                                    }
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </Modal>
                <div>
                    <Modal
                        open={this.state.alert.show}
                        onClose={() => this.handleDeleteUser(false)}
                        closeOnEsc={false}
                        closeOnOverlayClick={false}
                        styles={{maxWidth: "1000px"}}>

                        <div className="container">
                            <div className="row">
                                <div className="col-12 col-md-12">
                                    <div className="checkout_details_area mt-15 clearfix">

                                        <div className="cart-page-heading mb-10">
                                            <h5>Alert</h5>
                                        </div>
                                        <form>
                                            <div className="row ">

                                                <div className="col-md-12 mb-10">
                                                    Are you sure you want to delete (<b>{this.state.user.email}</b>)?
                                                </div>
                                                <div className="col-md-12 ">
                                                    <button type="button"
                                                            className="btn btn-outline-dark font-14 pull-right "
                                                            onClick={() => this.handleDeleteUser(false, null, true)}>
                                                        Proceed
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </Modal>
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
        fetch_user_props: state.account.fetch_users,
        fetch_role_props: state.account.fetch_roles,
        save_user_props: state.account.save_user,
        error: state.error.error,
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        fetch_user_list: (params) => {
            dispatch(_fetchAllUser(params));
        },
        fetch_roles_list: () => {
            dispatch(_fetchAllRoles());
        },
        delete_user: (data) => {
            dispatch(_deleteUser(data))
        },
        save_user: (data) => {
            dispatch(_saveUser(data));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(UserManagement);
