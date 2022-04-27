import React, {useContext} from 'react';
import {ListItem, List} from "@mui/material"
import NavigationMenuItem from "./NavigationMenuItem"
import NavigationContext from "../../contexts/NavigationContext"

function NavigationMenu({direction}) {
    const {routes} = useContext(NavigationContext)
    return (
        <nav>
            <List sx={{display: "flex", flexDirection: direction, padding: 0}}>
                {Object.keys(routes).map((routeName, index) => {
                    return (
                        <ListItem
                            key={"navbar-item-" + index}
                        >
                            <NavigationMenuItem
                                routeName={routeName}
                                url={routes[routeName]}
                            />
                        </ListItem>
                    )
                })}
            </List>
        </nav>
    );
}

NavigationMenu.defaultProps = {
    direction: "row"
}

export default NavigationMenu;
