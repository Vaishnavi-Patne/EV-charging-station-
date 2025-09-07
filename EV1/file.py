from flask import Flask, request, redirect, render_template_string
import string, random

app = Flask(__name__)

# In-memory database (dictionary)
url_map = {}

# Generate a random short code
def generate_short_code(length=6):
    chars = string.ascii_letters + string.digits
    return ''.join(random.choice(chars) for _ in range(length))

# Home page with form
@app.route('/', methods=['GET', 'POST'])
def home():
    if request.method == 'POST':
        original_url = request.form['url']
        short_code = generate_short_code()
        url_map[short_code] = original_url
        short_url = request.host_url + short_code
        return render_template_string('''
            <h2>Short URL Created!</h2>
            <p>Original: {{ original_url }}</p>
            <p>Short: <a href="{{ short_url }}">{{ short_url }}</a></p>
            <a href="/">Make another</a>
        ''', original_url=original_url, short_url=short_url)
    return '''
        <h2>Simple URL Shortener</h2>
        <form method="post">
            <input type="url" name="url" placeholder="Enter URL here" required>
            <button type="submit">Shorten</button>
        </form>
    '''

# Redirect short URL to original
@app.route('/<short_code>')
def redirect_to_url(short_code):
    if short_code in url_map:
        return redirect(url_map[short_code])
    return "<h2>URL not found!</h2>", 404

if __name__ == '__main__':
    app.run(debug=True)
