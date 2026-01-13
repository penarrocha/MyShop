<div class="bg-white rounded-lg shadow-lg p-6 category-card cursor-pointer {{ $class }}">
    <div class="text-4xl text-primary-500 mb-4 h-48 overflow-hidden">
        <a href="{{ route('categories.show', $category) }}" class="text-primary-600 font-semibold hover:text-primary-700 transition"><x-cloudinary::image
                :public-id="$category->image"
                :alt="$category->name"
                class="w-full h-full object-cover
                   transition-transform duration-300
                   group-hover:scale-105" /></a>
    </div>
    <h4 class="text-xl font-bold mb-2 text-gray-900">{{ $category->name }}</h4>
    <p class="text-gray-600 mb-4">{{ $category->description }}</p>

    <a href="{{ route('categories.show', $category) }}" class="text-primary-600 font-semibold hover:text-primary-700 transition">Ver Productos →</a>
</div>