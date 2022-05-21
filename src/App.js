import { blue, green, grey, purple, red } from "@mui/material/colors";
import { createTheme, ThemeProvider } from "@mui/material/styles";
import { Box } from "@mui/system";
import { deepmerge } from "@mui/utils";
import { Route, Routes } from "react-router-dom";
import "./App.css";
import MenuBar from "./components/MenuBar";
import { UserProvider } from "./components/UserContext";
import Index from "./pages/Index";
import PlayerStats from "./pages/PlayerStats";
import PlayGrid from "./pages/PlayGrid";

function App() {
  const theme = createTheme(
    deepmerge({
      components: {
        MuiButton: {
          variants: [
            {
              props: { variant: "flat" },
              style: {
                color: "white",
              },
            },
          ],
        },
      },
      palette: {
        mode: "dark",
        primary: {
          main: purple[500],
          dark: purple[900],
        },
        secondary: {
          main: green[500],
          dark: green[900],
        },
        background: {
          primary: grey[900],
        },
        blue: {
          main: blue[500],
          dark: blue[900],
        },
        red: {
          main: red[500],
          dark: red[900],
        },
      },
      typography: {
        fontFamily: '"Poppins", "Helvetica", "Arial", sans-serif',
      },
    })
  );

  return (
    <ThemeProvider theme={theme}>
      <UserProvider>
        <Box>
          <MenuBar />
          <Routes>
            <Route path="/" element={<Index />} />
            <Route path="playgrid" element={<PlayGrid />} />
            <Route path="stats" element={<PlayerStats />} />
          </Routes>
        </Box>
      </UserProvider>
    </ThemeProvider>
  );
}

export default App;
