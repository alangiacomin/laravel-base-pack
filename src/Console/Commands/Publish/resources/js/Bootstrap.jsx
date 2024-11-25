import {useEffect, useState} from "react";
import Router from "./Router";
import AuthContext from "./AuthContext";
import {userLoad} from "./apis/apiUser";

const Bootstrap = () => {
    const [user, setUser] = useState(null);
    const [ready, setReady] = useState(false);

    useEffect(() => {
        userLoad()
            .then((data)=>{
                setUser(data);
                setReady(true);
            });
    }, []);

    return ready && (
        <AuthContext.Provider value={{user, setUser}}>
            <Router />
        </AuthContext.Provider>
    );
}

export default Bootstrap;
