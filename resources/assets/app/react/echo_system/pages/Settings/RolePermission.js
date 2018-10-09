import React, {Component} from 'react';
import {connect} from 'react-redux';

import {getSession} from "../../../store/helper/auth-helper";
import history from "../../../History";
import Header from "../../layout/Header";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {_fetchAllRoles} from "../../../store/action/action-acounts";
import {_savePermission, _fetchAllPermission, _deletePermission} from "../../../store/action/action-setting";
import Modal from "react-responsive-modal";
import Pagination from "../sub_components/Pagination";

const queryString = require('query-string');

class RolePermission extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);
        this.hammad = [];
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
            permission: {
                id: "",
                name: "",
                display_name: "",
                description: "",
                roles: []
            },
            currentCheckedRoles: [],
            is_role_visible: false,
            error: ''

        };
    }

    /**
     * componentWillMount [react default life cycle functions]
     */
    componentWillMount() {
        this.props.fetch_permission_list(this._builtQuery());
        this.props.fetch_roles_list();
    }

    /**
     * componentWillReceiveProps [react default life cycle functions]
     * @param NextProps
     */
    componentWillReceiveProps(NextProps) {
        this.setState({"error": NextProps.error});
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {

    }

    /**
     * handleChange
     */
    handleChange(event) {
        const {name, value} = event.target;
        const {permission} = this.state;
        this.setState({
            permission: {
                ...permission,
                [name]: value
            }
        });
    }

    /**
     * handleSavePermission
     */
    handleCheckBox(e) {
        let roles = (this.state.permission.roles.length === 0) ? [] : this.state.permission.roles
        if (e.target.checked) {
            roles.push(JSON.parse(e.target.value));
        }
        else {
            for (let i = 0; i <= (roles.length - 1); i++) {
                if (roles[i].id === JSON.parse(e.target.value).id) {
                    roles.splice(i, 1);
                }
            }
        }
        this.setState({
            permission: {
                ...this.state.permission,
                roles: roles
            }
        });
    }

    /**
     * handleSavePermission
     */
    handleSavePermission() {
        this.setState({error: ''});
        this.props.save_permission(this.state.permission);

        setTimeout(function () {
            if (this.state.error === '') {
                toast.success("Congratulation! permission Saved successfully.");
                this.setState({
                    ...this.state,
                    permission: {
                        id: '',
                        name: '',
                        display_name: '',
                        description: '',
                        roles: []
                    },
                    is_role_visible: false
                });
                this.props.fetch_permission_list(this._builtQuery());
            }
        }.bind(this), 1000);
    }

    /**
     * handleEditPermission
     */
    handleEditPermission(permission) {
        this.setState({
            permission: {
                id: permission.id,
                name: permission.name,
                display_name: permission.display_name,
                description: permission.description,
                roles: permission.roles
            }
        });
        this.setState({is_role_visible: true});
    }

    /**
     * handleDeletePermission
     */
    handleDeletePermission(permission = null, _isOpen, is_confirm = false) {
        this.setState({
            permission: {
                id: permission.id,
                name: permission.name,
                display_name: permission.display_name,
                description: permission.description,
                roles: permission.roles
            }
        });
        if (is_confirm !== false) {
            this.props.delete_permission(permission);
            setTimeout(function () {
                toast.success("Congratulation! permission deleted successfully.");
                this.setState({
                    permission: {
                        id: '',
                        name: '',
                        display_name: '',
                        description: '',
                        roles: []
                    }
                });
                this.props.fetch_permission_list(this._builtQuery());
            }.bind(this), 500)
        }
        if (_isOpen === true) {
            this.setState({alert: {show: true}});
        } else if (_isOpen === false) {
            this.setState({alert: {show: false}});
        }
    }

    /**
     * _permissionList
     */
    _permissionList() {
        if (this.props.fetch_permissions !== '') {
            return this.props.fetch_permissions.permissions.map((permission, index) => {
                return (
                    <tr key={index}>
                        <td>{permission.name}</td>
                        <td>{permission.display_name}</td>
                        <td>{permission.description}</td>
                        <td>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item" onClick={() => this.handleEditPermission(permission)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item"
                                   onClick={() => this.handleDeletePermission(permission, true)}><i
                                    className='fa fa-trash'></i> Delete</a>
                            </div>
                        </td>

                    </tr>
                )
            })

        }
    }


    /**
     * findValue
     */
    findValue(roleObject, key) {
        for (let i = 0; i <= (roleObject.length - 1); i++) {
            if (roleObject[i].id === key) {
                return roleObject[i].id;
                break;
            }
        }
    }

    /**
     * _builtQuery
     */
    _builtQuery() {
        return queryString.parse(location.search)
    }

    /**
     * _roleList
     */
    _roleList() {
        // debugger
        if (this.props.fetch_roles !== '') {
            return this.props.fetch_roles.roles.map((role, index) => {
                return (
                    <div className="form-check" key={index}>
                        <label className="form-check-label">
                            <input className="form-check-input"
                                   type="checkbox"
                                   value={JSON.stringify(role)}
                                   onChange={(e) => this.handleCheckBox(e)}
                                   aria-label="..."
                                   checked={this.findValue(this.state.permission.roles, role.id) == role.id}/> {role.display_name}
                        </label>
                    </div>
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
                            <div className="col-12 col-md-12 mb-15">
                                <div className="regular-page-content-wrapper clear-10">
                                    <div className="regular-page-text">
                                        <div className="card">
                                            <div className="card-body">
                                                <h5 className="card-title">Function Access</h5>
                                                <hr/>
                                                <div className="checkout_details_area clearfix">
                                                    <form>
                                                        <pre><code>{JSON.stringify(this.state.error.data)}</code></pre>
                                                        <div className="row">
                                                            <div className="col-md-4 mb-3">
                                                                <input type="text" className="form-control" name="name"
                                                                       placeholder="Name"
                                                                       value={this.state.permission.name}
                                                                       onChange={(event) => this.handleChange(event)}/>
                                                            </div>
                                                            <div className="col-md-4 mb-3">
                                                                <input type="text" className="form-control"
                                                                       name="display_name"
                                                                       placeholder="Display Name"
                                                                       value={this.state.permission.display_name}
                                                                       onChange={(event) => this.handleChange(event)}/>
                                                            </div>
                                                            <div className="col-md-4 mb-3">
                                                                <input type="text" className="form-control"
                                                                       name="description"
                                                                       placeholder="Description"
                                                                       value={this.state.permission.description}
                                                                       onChange={(event) => this.handleChange(event)}/>
                                                            </div>
                                                            <div className="col-12 col-md-12">
                                                                <div className="form-check form-check-inline">
                                                                    {this.state.is_role_visible && this._roleList()}
                                                                </div>

                                                            </div>
                                                            <div className="col-md-12 mb-3">
                                                                <button type="button"
                                                                        className="btn btn-outline-dark font-14 pull-right"
                                                                        onClick={() => this.handleSavePermission()}>Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <table className="table table-bordered mb-30">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Display Name</th>
                                                        <th>Description</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._permissionList()}

                                                    </tbody>
                                                </table>
                                                {this.props.fetch_permissions.meta && this.props.fetch_permissions.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetch_permissions.meta}
                                                            url={location.pathname}/>
                                                }
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <Modal
                                open={this.state.alert.show}
                                onClose={() => this.handleDeletePermission(this.state.permission, false)}
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
                                                            Are you sure you want to delete
                                                            (<b>{this.state.permission.name}</b>)?
                                                        </div>
                                                        <div className="col-md-12 ">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14 pull-right "
                                                                    onClick={() => this.handleDeletePermission(this.state.permission, false, true)}>
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
    // debugger;
    return ({
        fetch_permissions: state.setting.fetch_permissions,
        fetch_roles: state.account.fetch_roles,
        error: state.account.error
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        fetch_roles_list: () => {
            dispatch(_fetchAllRoles())
        },
        fetch_permission_list: (params) => {
            dispatch((_fetchAllPermission(params)));
        },
        save_permission: (params) => {
            dispatch(_savePermission(params));
        },
        delete_permission: (params) => {
            dispatch(_deletePermission(params));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(RolePermission);
