<table>
  <thead><tr><td>Variable</td><td>Value</td></tr></thead>
  <tbody>
    <% ENV.each do |k,v| %>
      <tr><td><%= k %></td><td><%= v %></td></tr>
    <% end %>
  </tbody>
</table>
