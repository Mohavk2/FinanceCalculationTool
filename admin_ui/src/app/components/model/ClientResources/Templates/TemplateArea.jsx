import ResourceTable from "../../ResourceTable"
import {Stack} from "@mui/material"
import useApiResource from "../../../../api/useApiResource"
import ResourcePagination from "../../ResourcePagination"
import React, {useState} from "react"
import Button from "@mui/material/Button"
import AddCircleOutlineIcon from "@mui/icons-material/AddCircleOutline"
import Search from "../../../shared/SearchFields/Search"
import TemplateForm from "./TemplateForm"
import RemoveTemplateDialog from "./RemoveTemplateDialog"

function TemplateArea({operationId, defaultCategoryId, tableParams}) {

    const url = '/client_resources/templates'
    const query = {
        paginate: 10,
        filter: {
            operation_id: operationId
        },
        include: ['category', 'operation']
    }
    const {status, meta, items, error, changeFilters, changePage, create, update, remove} = useApiResource(url, query)

    const [templateForm, setTemplateForm] = useState()
    const [removeItemDialog, setRemoveItemDialog] = useState()

    const closeTemplateForm = () => {
        setTemplateForm(null)
    }

    const openCreateForm = () => {
        setTemplateForm(
            <TemplateForm
                title="Create template"
                operationId={operationId}
                handleClose={closeTemplateForm}
                handleAccept={data => create(data)}
                initCategoryId={defaultCategoryId}
            />
        )
    }
    const openUpdateForm = (item) => {
        setTemplateForm(
            <TemplateForm
                title="Update template"
                operationId={operationId}
                handleClose={closeTemplateForm}
                handleAccept={data => update(item.id, data)}
                id={item.id}
                initCategoryId={item.included.category.id}
                initTemplateName={item.attributes.name}
            />
        )
    }

    const openRemoveItemDialog = (item) => {
        setRemoveItemDialog(
            <RemoveTemplateDialog
                item={item}
                itemColor="red"
                closeHandler={closeRemoveItemDialog}
                acceptHandler={acceptRemove}
            />
        )
    }

    const closeRemoveItemDialog = () => {
        setRemoveItemDialog(null)
    }

    const acceptRemove = (id) => {
        remove(id)
    }

    return (
        <>
            {templateForm}
            {removeItemDialog}
            <Stack spacing={1}>
                <Search searchHandler={value => changeFilters({name: value})}/>
                <Button variant="contained" onClick={openCreateForm}>
                    <AddCircleOutlineIcon fontSize="large"/>
                </Button>
                <ResourcePagination
                    meta={meta}
                    changePageHandler={(e, value) => changePage(value)}
                    sx={{
                        marginLeft: 'auto !important',
                        marginRight: 'auto !important',
                    }}
                />
                <ResourceTable
                    editHandler={openUpdateForm}
                    removeHandler={openRemoveItemDialog}
                    status={status}
                    items={items}
                    allowedFields={tableParams.fields}
                    error={error}
                />
            </Stack>
        </>
    )
}

export default TemplateArea