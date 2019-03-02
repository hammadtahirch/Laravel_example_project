import React from "react";

function DataNotFound(props) {
    let html = "";
    switch (props.type) {
        case "table":
            html = <tr>
                <td colSpan={props.colSpan}
                >{props.message}</td>
            </tr>;
            break;
    }
    return (html);
}

export default DataNotFound;