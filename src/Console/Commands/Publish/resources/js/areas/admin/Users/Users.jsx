import ProtectedContent from "../../../ProtectedContent";
import {PermissionEnum} from "../../../enums/PermissionEnum";
import {useEffect, useState} from "react";
import {userAll} from "../../../apis/apiUser";
import User from "./User";

const Users = () => {
    const [users, setUsers] = useState([]);
    useEffect(() => {
        userAll()
            .then((data) => {
                setUsers(data);
            })
    }, []);

    // const removeRole = useCallback((userId, role) => {
    //     userRemoveRole({id: userId, role: role})
    //         .then((resp) => {
    //             setUsers(users.map(user => users.filter((u) => u.id !== userId).find(u => u.id === user.id) || resp));
    //         });
    //     return false;
    // }, [users]);

    return (
        <ProtectedContent perm={PermissionEnum.ADMIN_USERS}>
            <h1>USERS</h1>
            <ul>
                {users && users.map((user) => (
                    <User key={user.id} user={user}/>
                ))}
            </ul>
        </ProtectedContent>
    );
}

export default Users;
