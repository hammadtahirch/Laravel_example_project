import React, {Component} from 'react';
import {connect} from 'react-redux';

class Pagination extends Component {
    /**
     * [constructor description]
     * @param {[type]} props [description]
     */
    constructor(props) {
        super(props);
        this.state = {
            total: this.props.meta.pagination.total,
            count: this.props.meta.pagination.count,
            per_page: this.props.meta.pagination.per_page,
            current_page: this.props.meta.pagination.current_page,
            total_pages: this.props.meta.pagination.total_pages,
            nextPage: this.props.meta.pagination.current_page + 1,
            prevPage: this.props.meta.pagination.current_page - 1,
            parts: [],
            start: 1,
            end: 5,
        }

    }

    componentWillMount() {
        if (this.state.prevPage < 1) {
            this.setState({prevPage: null});
        }
        if (this.state.nextPage > this.state.total_pages) {
            this.setState({nextPage: null});
        }
        this.state.parts.push({
            title: 'previous',
            url: this.state.prevPage
        });
        if (this.state.current_page > 1) {
            this.state.start = this.state.current_page - 2;
            this.state.end = this.state.current_page + 2;
        }
        if (this.state.start < 1) {
            this.state.start = 1;
        }
        if (this.state.end > this.state.total_pages) {
            this.state.end = this.state.total_pages;
        }
        if (this.state.start > 1) {
            this.state.parts.push({
                title: 1,
                url: 1
            })
        }
        if (this.state.start > 2) {
            this.state.parts.push({
                title: '...'
            })
        }
        for (var i = this.state.start; i <= this.state.end; i++) {
            this.state.parts.push({
                title: i,
                url: i
            })
        }
        if (this.state.end < this.state.total_pages - 1) {
            this.state.parts.push({
                title: '...'
            })
        }
        if (this.state.end < this.state.total_pages) {
            this.state.parts.push({
                title: this.state.total_pages,
                url: this.state.total_pages
            })
        }
        this.state.parts.push({
            title: 'next',
            url: this.state.nextPage
        });
    }

    _paging(part, index) {
        let _render = '';
        switch (part.title) {
            case 'previous':
                _render = <li className="page-item" key={index}>
                    <a className="page-link" href={"/admin/user_management?page=" + part.url}>
                        <i className="fa fa-angle-left"></i>
                    </a>
                </li>;
                break;
            case 'next':
                _render = <li className="page-item" key={index}>
                    <a className="page-link" href={"/admin/user_management?page=" + part.url}>
                        <i className="fa fa-angle-right"></i>
                    </a>
                </li>
                break;
            default:
                if (part.title === this.state.current_page) {
                    _render = <li className="page-item" key={index}>
                        <a className="page-link">{part.title}</a>
                    </li>
                } else {
                    _render = <li className="page-item" key={index}>
                        <a className="page-link" href={"?page=" + part.url}>{part.title}</a>
                    </li>
                }

                break;
        }
        return _render;
    }

    /**
     * [render description]
     * @return {[type]} [description]
     */
    render() {
        return (
            <nav aria-label="navigation">
                <ul className="pagination  justify-content-center mb-50">
                    {
                        this.state.parts.map((part, index) => {
                            return this._paging(part, index)
                        })

                    }

                </ul>
            </nav>
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

export default connect(mapStateToProp, mapDispatchToProp)(Pagination);
