import React, { useRef, useState, useEffect } from "react";
import playIcon from "../assets/img/play-btn.png";

import { Reveal } from './Reveal';

export const VideoPlayer = ({ video, poster }) => {
    const videoRef = useRef(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const [generatedPoster, setGeneratedPoster] = useState(null);

    useEffect(() => {
        if (poster || !videoRef.current) return;

        const videoEl = videoRef.current;

        const capturePoster = () => {
            const canvas = document.createElement("canvas");
            canvas.width = videoEl.videoWidth;
            canvas.height = videoEl.videoHeight;
            canvas.getContext("2d").drawImage(videoEl, 0, 0);
            setGeneratedPoster(canvas.toDataURL("image/jpeg"));
            videoEl.currentTime = 0;
        };

        videoEl.addEventListener("seeked", capturePoster, { once: true });
        videoEl.currentTime = 0.1;

        return () => videoEl.removeEventListener("seeked", capturePoster);
    }, [poster]);

    const togglePlayPause = () => {
        if (videoRef.current) {
            if (videoRef.current.paused) {
                videoRef.current.play();
            } else {
                videoRef.current.pause();
            }
        }
    };

    return (
        <section className="max-md:-mx-[5vw] w-screen md:w-full h-full">
            <Reveal className="group relative w-full h-full" direction="bottom">
                <video
                    ref={videoRef}
                    className="w-full h-full object-cover max-h-[85vh]"
                    src={video}
                    poster={poster || generatedPoster || undefined}
                    playsInline
                    preload="metadata"
                    onPlay={() => setIsPlaying(true)}
                    onPause={() => setIsPlaying(false)}
                    onEnded={() => setIsPlaying(false)}
                />

                <button
                    type="button"
                    onClick={togglePlayPause}
                    aria-label={isPlaying ? "Pause" : "Play"}
                    className="absolute inset-0 z-10 flex items-center justify-center cursor-pointer"
                >
                    <span className="absolute inset-0 bg-gradient-to-t from-black to-transparent to-80% opacity-50 transition-all group-hover:opacity-80" />

                    <img
                        src={playIcon}
                        alt=""
                        className={`relative max-w-[20%] rounded-full transition-all group-hover:scale-110 ${isPlaying ? 'opacity-0' : ''}`}
                    />
                </button>
            </Reveal>
        </section>
    );
};