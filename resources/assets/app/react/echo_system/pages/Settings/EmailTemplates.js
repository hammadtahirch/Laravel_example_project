import React, {Component} from 'react';
import {connect} from 'react-redux';

import Header from "../../layout/Header";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Pagination from "../sub_components/Pagination";
import Modal from "react-responsive-modal";
import {getSession} from "../../../store/helper/auth-helper";
import store from "../../../store";
import ActionTypes from "../../../store/constant/constant";
import ValidationErrors from "../sub_components/ValidationErrors";
import {_deleteTemplate, _fetchAllTemplates, _saveTemplate} from "../../../store/action/action-email_templates";
import DataNotFound from "../sub_components/DataNotFound";
import RichTextEditor from 'react-rte';
import Switch from '../sub_components/Switch';

const queryString = require('query-string');

class EmailTemplates extends Component {

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
            editorValue: RichTextEditor.createEmptyValue(),
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
            filter: {
                filterName: '',
                filterValue: ''
            },
            template: {
                id: "",
                key: "",
                subject: "",
                from_email: "",
                from_name: "",
                email_body: "",
                merge_field: "",
                is_enabled: true,
            }

        };
    }

    onChange(event) {
        this.setState({editorValue: event});
    };

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
        this.props.fetch_templates(this._builtQuery());
    }


    handleSwitch(value) {
        // debugger;
        console.log(value);
        const {template} = this.state;
        this.setState({
            template: {
                ...template,
                is_enabled: value
            }
        });
    }

    /**
     * handleChange
     */
    handleChange(event) {
        const {name, value} = event.target;
        const {template} = this.state;
        this.setState({
            template: {
                ...template,
                [name]: value
            }
        });
    }

    /**
     * handleFilter
     * @param event
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
     * handleIsModelOpen
     * @param _isOpen
     */
    handleIsModelOpen(_isOpen) {
        if (_isOpen === true) {
            this.setState({modal: {show: true}});
        } else if (_isOpen === false) {
            this.setState({modal: {show: false}});
            this.setState(
                {
                    template: {
                        id: "",
                        key: "",
                        subject: "",
                        from_email: "",
                        from_name: "",
                        email_body: "",
                        merge_field: "",
                        is_enabled: "",
                    }
                }
            );
            store.dispatch({type: ActionTypes.ERROR, payload: ''})
        }
    }

    /**
     * handleDeleteTemplate
     */
    handleDeleteTemplate(template = null, _isOpen, is_confirm = false) {
        this.setState({
            template: {
                id: template.id,
                key: template.key,
                subject: template.subject,
                from_email: template.from_email,
                from_name: template.from_name,
                email_body: template.email_body,
                merge_field: template.merge_field,
                is_enabled: template.is_enabled
            }
        });

        if (is_confirm !== false) {
            this.props.delete_template(template);
        }
        if (_isOpen === true) {
            this.setState({alert: {show: true}});
        } else if (_isOpen === false) {
            this.setState({alert: {show: false}});
        }
    }

    /**
     * handleEditTemplate
     * @param  template
     */
    handleEditTemplate(template) {
        debugger;
        this.setState(
            {
                template: {
                    id: template.id,
                    key: template.key,
                    subject: template.subject,
                    from_email: template.from_email,
                    from_name: template.from_name,
                    email_body: template.email_body,
                    merge_field: template.merge_field,
                    is_enabled: template.is_enabled
                },
                editorValue: RichTextEditor.createValueFromString(template.email_body, "html")
            }
        );
        this.handleIsModelOpen(true);
    }

    /**
     * handleModalSave
     */
    handleModalSave(template) {
        template["email_body"] = this.state.editorValue.toString("html");
        debugger;
        this.props.save_template(template);
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
     * handleSearch
     */
    handleSearch() {
        this.props.fetch_templates(this._builtQuery());
    }

    /**
     * _templateList
     * @param props
     */
    _templateList(props) {
        if (props.fetch_template_props !== '') {
            if (props.fetch_template_props.templates.length === 0) {
                return DataNotFound({type: "table", colSpan: "4", message: "Uh-oh! there is no template available."})
            }

            return props.fetch_template_props.templates.map((template, index) => {
                return (
                    <tr key={index}>
                        <td>{template.key}</td>
                        <td>{template.subject}</td>
                        <td>{template.from_name}</td>
                        <td>{template.from_email}</td>
                        <td>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item" onClick={() => this.handleEditTemplate(template)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item"
                                   onClick={() => this.handleDeleteTemplate(template, true)}><i
                                    className='fa fa-trash'></i> Delete</a>
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
                maxWidth: "700px",
            }
        }
        {
            (this.props.save_template_props !== "") && toast.success("Wow! Template Save Successfully.")
        }
        const {editorState} = this.state;
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
                                    <div className="regular-page-text mb-15">
                                        <div className="card">
                                            <div className="card-body">
                                                <h5 className="card-title">Email Templates</h5>
                                                <hr/>
                                                <div className="checkout_details_area clearfix">
                                                    <button
                                                        className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                        onClick={() => this.handleIsModelOpen(true)}>Create Template
                                                    </button>
                                                    <div className="clear-5"></div>
                                                    <form className="mb-30">
                                                        <div className="row ">
                                                            <div className="col-md-2">
                                                                <select className="form-control" name="filterName"
                                                                        onChange={(e) => this.handleFilter(e)}>
                                                                    <option value='filter_by'>Filter By</option>
                                                                    <option value='title'>Title</option>
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
                                                </div>

                                                <table className="table table-bordered mb-30">
                                                    <thead>
                                                    <tr>
                                                        <th>Key</th>
                                                        <th>Subject</th>
                                                        <th>From Name</th>
                                                        <th>From Email</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._templateList(this.props)}

                                                    </tbody>
                                                </table>
                                                {this.props.fetch_template_props.meta && this.props.fetch_template_props.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetch_template_props.meta}
                                                            url={location.pathname}/>
                                                }
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
                                                    (this.state.template.id == '' || this.state.template.id == null) ?
                                                        <h5>Add Template</h5> : <h5>Update Template</h5>
                                                }
                                            </div>

                                            <form>
                                                <div className="row ">

                                                    {(this.props.error !== "") &&
                                                    <ValidationErrors validationErrors={this.props.error.data}
                                                                      statusCode={this.props.error.status}/>
                                                    }


                                                    <div className="col-md-6 mb-3">
                                                        <label>Key<span>*</span></label>
                                                        <input type="text" className="form-control" name="key"
                                                               value={this.state.template.key}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
                                                    <div className="col-md-6 mb-3">
                                                        <label>Subject<span>*</span></label>
                                                        <input type="text" className="form-control" name="subject"
                                                               value={this.state.template.subject}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
                                                    <div className="col-md-6 mb-3">
                                                        <label>From Email<span>*</span></label>
                                                        <input type="text" className="form-control" name="from_email"
                                                               value={this.state.template.from_email}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
                                                    <div className="col-md-6 mb-3">
                                                        <label>From Name<span>*</span></label>
                                                        <input type="text" className="form-control"
                                                               name="from_name"
                                                               value={this.state.template.from_name}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
                                                    <div className="col-md-6 mb-3">
                                                        <label>Merge Field<span>*</span></label>
                                                        <input type="text" className="form-control"
                                                               name="merge_field"
                                                               value={this.state.template.merge_field}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
                                                    <div className="col-md-6 mb-3">
                                                        <Switch handleSwitch={(value) => this.handleSwitch(value)}
                                                                isChecked={this.state.template.is_enabled}/>
                                                    </div>
                                                    <div className="col-md-12 mb-3">
                                                        <label>Email Body<span>*</span></label>
                                                        <RichTextEditor
                                                            value={this.state.editorValue}
                                                            onChange={(e) => this.onChange(e)}/>
                                                    </div>

                                                    <div className="col-md-12 mb-3">
                                                        <button type="button"
                                                                className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                                onClick={() => this.handleModalSave(this.state.template)}>
                                                            {
                                                                (this.state.template.id == '' || this.state.template.id == null) ? 'Add' : 'Update'
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
                                onClose={() => this.handleDeleteTemplate(this.state.template, false)}
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
                                                            (<b>{this.state.template.title}</b>)?
                                                        </div>
                                                        <div className="col-md-12 ">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14 pull-right "
                                                                    onClick={() => this.handleDeleteTemplate(this.state.template, false, true)}>
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
function

mapStateToProp(state) {
    return ({
        fetch_template_props: state.template.fetch_templates,
        save_template_props: state.template.save_template,
        error: state.error.error
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function

mapDispatchToProp(dispatch) {
    return ({
        fetch_templates: (params) => {
            dispatch((_fetchAllTemplates(params)));
        },
        save_template: (params) => {
            dispatch((_saveTemplate(params)));
        },
        delete_template: (params) => {
            dispatch((_deleteTemplate(params)));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(EmailTemplates);