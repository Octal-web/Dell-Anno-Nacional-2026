import { faEraser, faX } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import axios from "axios";
import { useEffect, useState } from "react";
import Select from "react-select";

export const StoresSearch = ({
    allStores,
    setStores,
    isProcessing,
    setIsProcessing,
}) => {
    const [states, setStates] = useState([]);
    const [cities, setCities] = useState([]);

    const [selectedState, setSelectedState] = useState("");
    const [selectedCity, setSelectedCity] = useState("");

    useEffect(() => {
        const fetchStates = async () => {
            try {
                const response = await axios.get(route("Lojas.estados"));

                const data = await response.data;

                setStates(data.estados);
            } catch (error) {
                console.error(error);
            } finally {
                setIsProcessing(false);
            }
        };

        fetchStates();
    }, []);

    useEffect(() => {
        if (!selectedState) {
            setCities([]);
            setSelectedCity("");
            setStores(allStores);
            return;
        }

        const fetchCities = async (stateId) => {
            try {
                const response = await axios.post(route("Lojas.cidades"), {
                    estado_id: stateId.value,
                });

                const data = await response.data;

                setCities(data.cidades);
            } catch (error) {
                console.error(error);
            } finally {
                setIsProcessing(false);
            }
        };

        fetchCities(selectedState);
    }, [selectedState]);

    const handleSearch = (e) => {
        e.preventDefault();

        if (!selectedState) {
            setStores(allStores);
            return;
        }

        let filtered = allStores.filter(
            (store) => store.estado === selectedState.uf,
        );

        if (selectedCity) {
            filtered = filtered.filter(
                (store) => store.cidade === selectedCity.label,
            );
        }

        setStores(filtered);
    };

    const handleClear = () => {
        setSelectedState(null);
        setSelectedCity(null);
        setCities([]);
        setStores(allStores);
    };

    return (
        <div className="container max-w-small">
            <form
                data-lenis-prevent
                className="flex flex-col md:flex-row gap-5"
                onSubmit={handleSearch}
            >
                <Select
                    options={states}
                    value={selectedState}
                    onChange={(option) => {
                        setSelectedState(option);
                        setSelectedCity(null);
                    }}
                    placeholder="Selecione um estado..."
                    classNamePrefix="admin-select"
                    className="w-full"
                    isClearable
                    isLoading={isProcessing}
                    noOptionsMessage={() => "Nenhum estado encontrado"}
                />

                <Select
                    options={cities}
                    value={selectedCity}
                    onChange={(option) => setSelectedCity(option)}
                    placeholder="Selecione uma cidade..."
                    classNamePrefix="admin-select"
                    className="w-full"
                    isDisabled={!selectedState}
                    isClearable
                    isLoading={isProcessing}
                    noOptionsMessage={() => "Nenhuma cidade encontrada"}
                />

                <div className="flex">
                    <button
                        type="submit"
                        disabled={isProcessing}
                        className="lg:mx-4 border border-neutral-800 bg-white font-light text-center px-3 py-1.5 min-w-30 transition-all hover:bg-black hover:text-white uppercase"
                    >
                        Buscar
                    </button>

                    {(selectedState || selectedCity) && (
                        <button
                            type="button"
                            onClick={handleClear}
                            disabled={isProcessing}
                            className="border border-red-600 text-red-600 px-4 transition-all hover:bg-red-600 hover:text-white"
                        >
                            <FontAwesomeIcon icon={faX} />
                        </button>
                    )}
                </div>
            </form>
        </div>
    );
};
