@extends('doc')

@section('extra_js')
  <script>
    Flatdoc.run({
      fetcher: Flatdoc.file("/assets/doc/md/{{{ $cat }}}/{{{ $path }}}.md")
    });
  </script>
@endsection