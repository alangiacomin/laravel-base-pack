import {useEffect, useState} from 'react';
import Button from "./Button";

const App = () => {
    const [user, setUser] = useState(null);
    useEffect(() => {
        window.axios.get("user/loadUser")
            .then((response) => {
                console.log(response.data);
                setUser(response.data);
            });
    }, []);

    const loginUser = () => {
        console.log("login button");
        return window.axios.post("user/login")
            .then((response) => {
                console.log(response.data);
                setUser(response.data[2]);
            });
    }

    return (
        <>
            <h1 className="text-3xl font-bold underline">
                Hello world!
            </h1>
            <div>
                {(!user || !user.id) && (
                    <Button
                        title={"LOGIN"}
                        action={loginUser}/>)}
                {user && user.id && (
                    <Button
                        title={"LOGOUT"}
                        action={() => {
                            console.log("logout button");
                            return window.axios.post("user/logout")
                                .then((response) => {
                                    console.log(response.data);
                                    setUser(null);
                                });

                        }}/>)}
                {user && (
                    <>
                        <p>{user.id}</p>
                        <p>{user.email}</p>
                    </>
                )}
                <p>
                    <Button
                        title={"EVENTO"}
                        action={() => {
                            location.href = "/event"
                        }}
                    />
                </p>
            </div>
        </>
    );
}

export default App;
