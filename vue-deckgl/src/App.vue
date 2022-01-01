<template>
  <div id="app"></div>
</template>

<script>
import "mapbox-gl/dist/mapbox-gl.css";

import { MapboxLayer } from "@deck.gl/mapbox";
import { Deck } from "@deck.gl/core";
import { ScatterplotLayer } from "@deck.gl/layers";
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";

export default {
  data() {
    return {
      map: {},
      deck: {}
    }
  },
  mounted() {
    this.map = new mapboxgl.Map({
      container: "app",
      style: "mapbox://styles/mapbox/light-v10",
      // style: 'https://tiles.devsdev.com/style.json',
      center: [-33, 27],
      zoom: 9
    })

    this.deck = new Deck({
      gl: map.painter.context.gl,
      layers: [
        new ScatterplotLayer({
          id: "my-scatterplot",
          data: [{ position: [-74.5, 40], size: 100 }],
          getPosition: (d) => d.position,
          getRadius: (d) => d.size,
          getFillColor: [255, 0, 0]
        })
      ]
    })

    // wait for map to be ready
    map.on("load", () => {
      // add to mapbox
      map.addLayer(new MapboxLayer({ id: "my-scatterplot", deck }))

      // update the layer
      deck.setProps({
        layers: [
          new ScatterplotLayer({
            id: "my-scatterplot",
            data: [{ position: [-74.5, 40], size: 100 }],
            getPosition: (d) => d.position,
            getRadius: (d) => d.size,
            getFillColor: [0, 0, 255]
          })
        ]
      })
    })
  }
}
</script>

<style>
html,
body,
#app {
  margin: 0;
  width: 100%;
  height: 100%;
}
</style>
