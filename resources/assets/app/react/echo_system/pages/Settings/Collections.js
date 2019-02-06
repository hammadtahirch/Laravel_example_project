import React, {Component} from 'react';
import {connect} from 'react-redux';

import Header from "../../layout/Header";
import Loading from "../sub_components/Loading";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {_deleteCollection, _fetchAllCollection, _saveCollection} from "../../../store/action/action-collection";
import Pagination from "../sub_components/Pagination";
import Modal from "react-responsive-modal";
import {getSession} from "../../../store/helper/auth-helper";
import store from "../../../store";
import ActionTypes from "../../../store/constant/constant";
import ValidationErrors from "../sub_components/ValidationErrors";
import classNames from 'classnames'
import Dropzone from 'react-dropzone'

const queryString = require('query-string');

class Collections extends Component {

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
            filter: {
                filterName: '',
                filterValue: ''
            },
            collection: {
                id: "",
                title: "",
                description: "",
                dataUrl: "",
            }

        };
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
        this.props.fetch_collections(this._builtQuery());
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
                const {collection} = this.state;
                this.setState({
                    collection: {
                        ...collection,
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
     * handleChange
     */
    handleChange(event) {
        const {name, value} = event.target;
        const {collection} = this.state;
        this.setState({
            collection: {
                ...collection,
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
                    collection: {
                        id: '',
                        title: '',
                        description: '',
                        dataUrl: '',
                    }
                }
            );
            store.dispatch({type: ActionTypes.ERROR, payload: ''})
        }
    }

    /**
     * handleDeleteCollection
     */
    handleDeleteCollection(collection = null, _isOpen, is_confirm = false) {
        this.setState({
            collection: {
                id: collection.id,
                title: collection.title,
                description: collection.description,
            }
        });

        if (is_confirm !== false) {
            this.props.delete_collection(collection);
        }
        if (_isOpen === true) {
            this.setState({alert: {show: true}});
        } else if (_isOpen === false) {
            this.setState({alert: {show: false}});
        }
    }

    /**
     * handleEditUser
     * @param  collection
     */
    handleEditCollection(collection) {
        this.setState(
            {
                collection: {
                    id: collection.id,
                    title: collection.title,
                    description: collection.description,
                }
            }
        );
        this.handleIsModelOpen(true);
    }

    /**
     * handleModalSave
     */
    handleModalSave(collection) {
        this.props.save_collection(collection);
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
        this.props.fetch_collections(this._builtQuery());
    }

    /**
     * _collectionList
     * @param props
     */
    _collectionList(props) {
        if (props.fetch_collection_props !== '') {
            return props.fetch_collection_props.collections.map((collection, index) => {
                return (
                    <tr key={index}>
                        <td>{collection.title}</td>
                        <td>{collection.description}</td>
                        <td>
                            <a href="" className="dropdown-toggle"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <i className='fa fa-bars'></i>
                            </a>
                            <div className="dropdown-menu">
                                <a className="dropdown-item" onClick={() => this.handleEditCollection(collection)}><i
                                    className='fa fa-pencil'></i> Edit</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item"
                                   onClick={() => this.handleDeleteCollection(collection, true)}><i
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
                maxWidth: "500px",
            }
        }
        {
            (this.props.save_collection_props !== "") && toast.success("Wow! Collection Save Successfully.")
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
                                    <div className="regular-page-text mb-15">
                                        <div className="card">
                                            <div className="card-body">
                                                <h5 className="card-title">Collections</h5>
                                                <hr/>
                                                <div className="checkout_details_area clearfix">
                                                    <button className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                            onClick={() => this.handleIsModelOpen(true)}>Create
                                                        Collection
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
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this._collectionList(this.props)}

                                                    </tbody>
                                                </table>
                                                {this.props.fetch_collection_props.meta && this.props.fetch_collection_props.meta.pagination.total_pages > 1 &&
                                                <Pagination meta={this.props.fetch_collection_props.meta}
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
                                                    (this.state.collection.id == '' || this.state.collection.id == null) ?
                                                        <h5>Add Collection</h5> : <h5>Update Collection</h5>
                                                }
                                            </div>

                                            <form>
                                                <div className="row ">

                                                    {(this.props.error !== "") &&
                                                    <ValidationErrors validationErrors={this.props.error.data}
                                                                      statusCode={this.props.error.status}/>
                                                    }


                                                    <div className="col-md-6 mb-3">
                                                        <label>Title<span>*</span></label>
                                                        <input type="text" className="form-control" name="title"
                                                               value={this.state.collection.title}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
                                                    <div className="col-md-6 mb-3">
                                                        <label>Description<span>*</span></label>
                                                        <input type="text" className="form-control" name="description"
                                                               value={this.state.collection.description}
                                                               onChange={(e) => this.handleChange(e)}/>
                                                    </div>
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
                                                        {this.state.collection.dataUrl !== "" &&
                                                        <p>YaHu! the image selected.</p>}
                                                    </div>
                                                    <div className="col-md-12 mb-3">
                                                        <button type="button"
                                                                className="btn btn-outline-dark font-14 mb-30 pull-right"
                                                                onClick={() => this.handleModalSave(this.state.collection)}>
                                                            {
                                                                (this.state.collection.id == '' || this.state.collection.id == null) ? 'Add' : 'Update'
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
                                onClose={() => this.handleDeleteCollection(this.state.collection, false)}
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
                                                            (<b>{this.state.collection.title}</b>)?
                                                        </div>
                                                        <div className="col-md-12 ">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14 pull-right "
                                                                    onClick={() => this.handleDeleteCollection(this.state.collection, false, true)}>
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
    console.log(state);
    return ({
        error: state.error.error,
        fetch_collection_props: state.collection.fetch_collections,
        save_collection_props: state.collection.save_collection
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        fetch_collections: (params) => {
            dispatch((_fetchAllCollection(params)));
        },
        save_collection: (params) => {
            dispatch((_saveCollection(params)));
        },
        delete_collection: (params) => {
            dispatch((_deleteCollection(params)));
        }
    })
}

export default connect(mapStateToProp, mapDispatchToProp)(Collections);
