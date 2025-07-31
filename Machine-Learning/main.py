from google import generativeai
import os
import requests
from dotenv import load_dotenv
load_dotenv()

api_key = os.getenv("API_KEY")
generativeai.configure(api_key=api_key)

model = generativeai.GenerativeModel("gemini-2.0-flash")
chat = model.start_chat()

def get_pokemon_info(name):
    url = f"https://pokeapi.co/api/v2/pokemon/{name.lower()}"
    response = requests.get(url)
    if response.status_code == 200:
        data = response.json()
        types = ', '.join([t['type']['name'] for t in data['types']])
        height = data['height']
        weight = data['weight']
        abilities = ', '.join([a['ability']['name'] for a in data['abilities']])
        stats = ', '.join([f"{s['stat']['name']}: {s['base_stat']}" for s in data['stats']])
        return (
            f"{name.capitalize()} adalah Pokémon bertipe {types}. "
            f"Tinggi: {height}, Berat: {weight}. "
            f"Kemampuan: {abilities}. "
            f"Statistik: {stats}."
        )
    else:
        return None

def search_pokemon_in_text(prompt):
    # Try to find a Pokémon name in the prompt using the PokéAPI list
    poke_list_url = "https://pokeapi.co/api/v2/pokemon?limit=10000"
    response = requests.get(poke_list_url)
    if response.status_code == 200:
        all_pokemon = [p['name'] for p in response.json()['results']]
        for word in prompt.lower().split():
            if word in all_pokemon:
                return word
    return None

def chat_inference(prompt):
    # Try to extract Pokémon name from prompt
    pokemon_name = search_pokemon_in_text(prompt)
    if pokemon_name:
        info = get_pokemon_info(pokemon_name)
        if info:
            return info

    # If not found, try to answer with Gemini AI
    response = chat.send_message(
        f"Jawab pertanyaan ini dengan pengetahuan Pokémon dan gunakan data dari https://pokeapi.co jika memungkinkan: {prompt}"
    )
    return response.text if hasattr(response, "text") else str(response)
