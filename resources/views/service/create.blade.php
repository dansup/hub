@extends('layouts.bulma')

@section('content')
<section class="section">
  <div class="container">
    <h1 class="title is-centered">Add a Service</h1>
    <h2 class="subtitle is-centered">Add your service or website to Hub! Verification is required</h2>
    <hr>
    <form action="/service/add" method="post">
    {!! csrf_field() !!}
    <div class="columns">
      <div class="column is-8">
        <p class="control">
          <div class="columns">
            <div class="column is-3">
              <span class="tag">
                Service Type
              </span>
            </div>
            <div class="column is-8">
              <span class="select">
                <select name="service_type">
                  <option>Select a type...</option>
                  <option value="website">Website</option>
                  <option value="bitcoin">Bitcoin</option>
                  <option value="irc">IRCd</option>
                  <option value="mail">Mail Server</option>
                  <option value="other">Other</option>
                </select>
              </span>
            </div>
          </div>
        </p>
        <p class="control">
          <div class="columns">
            <div class="column is-3">
              <span class="tag">
                Name
              </span>
            </div>
            <div class="column is-8">
              <input class="input" type="text" name="name" placeholder="socialnode">
            </div>
          </div>
        </p>
        <p class="control">
          <div class="columns">
            <div class="column is-3">
              <span class="tag">
                Website
              </span>
            </div>
            <div class="column is-8">
              <input class="input" type="text" name="url" placeholder="http://socialno.de">
            </div>
          </div>
        </p>
        <p class="control">
          <div class="columns">
            <div class="column is-3">
              <span class="tag">
                Description 
              </span>
            </div>
            <div class="column is-8">
              <textarea class="textarea" name="description" placeholder="A federated social network"></textarea>
            </div>
          </div>
        </p>
        <p class="control">
          <button type="submit" class="button is-primary">Submit</button>
          <button type="reset" class="button">Cancel</button>
        </p>

      </div>
    </form>
    </div>
  </div>
</section>
@endsection